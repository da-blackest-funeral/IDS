<?php

    namespace App\Services\Helpers;

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    /*
     * todo возможно сделать наследование от класса ProductHelper
     *
     * при этом сделать интерфейс, в сервис провайдере биндить в зависимости от категорий,
     * и таким образом получится универсальные классы хелперов
     *
     * аналогично можно поступить с классами SalaryHelper и OrderHelper,
     * т.к. могут возникнуть проблемы с универсальностью
     *
     * тогда ко всем этим классам обращаться через интерфейс и биндить в сервис провайдере
     */

    class MosquitoSystemsHelper extends ProductHelper
    {
        /**
         * This method determines the logic of
         * how to make salary for new mosquito system product.
         * This method is entry point of calculating salary for installer.
         *
         * @param ProductInOrder $productInOrder
         * @return void
         */
        public static function updateOrCreateSalary(ProductInOrder $productInOrder) {
            $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id);

            $count = static::countProductsWithInstallation($productInOrder);
            $countOfAllProducts = static::countOfProducts($productInOrder->order->products);
            $productsWithMaxInstallation = static::productsWithMaxInstallation($productInOrder);

            $productsOfTheSameTypeExists =
                /*
                 * Need to determine if products of the same type
                 * that current exists.
                 * Because new product had been already created,
                 * we need to skip them
                 */
                $products->get()->reject(function ($product) use ($productInOrder) {
                    return $product->id == $productInOrder->id ||
                        $product->id == oldProduct('id');
                })->isNotEmpty();

            /*
             * сюда передается productInOrder с id=3, но при этом
             * в заказе уже существуют товары с id = 1 и с id = 3,
             * являющиеся одним и тем же товаром, поэтому нужно пропускать старый
             * (сохраненный в сессии) товар, при этом только со страницы обновления товара
             */

            if ($productsOfTheSameTypeExists && !is_null(SalaryHelper::getSalary($productInOrder))) {
                /*
                 * Условие звучит так: если в заказе уже есть такой же товар с монтажом, и добалвяется
                 * товар без монтажа, то зп не пересчитывается. Если в заказе уже есть товар с монтажом, кроме нынешнего,
                 * у которого монтаж убирается, то зп тоже не пересчитывается
                 *
                 * НО, если в заказе уже есть монтаж, а в нынешнем продукте его нет, то з\п должна пересчитываться
                 * можно брать $count - request->count()
                 *
                 * расчет зарплаты при добавлении товаров одинакового типа
                 * если добавлено несколько товаров одинакового типа, то при расчете з\п
                 * берется монтаж с максимальной ценой, а количество равняется всем товарам данного типа,
                 * у которых задан монтаж, даже если он другой
                 */

                SalaryHelper::updateSalary(
                    sum: static::calculateInstallationSalary(
                        productInOrder: $productsWithMaxInstallation->first(),
                        count: $count,
                    ),
                    productInOrder: $productInOrder,
                );

            } elseif (!$countOfAllProducts || $count) {
                SalaryHelper::make($productInOrder->order);
            }
        }

        /**
         * Calculates salary for specified product, count, and, if necessary,
         * for specified installation.
         *
         * If $installation parameter equals to null,
         * method uses installation_id from given product
         *
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @param $installation
         * @return int
         */
        public static function calculateInstallationSalary(
            ProductInOrder $productInOrder,
            int            $count,
                           $installation = null
        ): int {

            if (fromUpdatingProductPage() && ProductHelper::oldProductHasInstallation()) {
                $count -= oldProductsCount();
            }

            $salary = Calculator::getInstallationSalary(
                installation: $installation ?? $productInOrder->installation_id,
                count: $count,
                /*
                 * todo подумать как это можно исправить
                 * обычно, ProductInOrder не имеет поля type_id,
                 * но в этот метод передается результат с left join
                 */
                typeId: $productInOrder->type_id
            );

            if ($salary != null) {
                $result = $salary->salary;
            } else {
                $salary = Calculator::maxCountSalary(
                    installation: $installation ?? $productInOrder->installation_id,
                    typeId: $productInOrder->type_id
                );

                if (is_null($salary)) {
                    return OrderHelper::salaries($productInOrder->order);
                }

                $missingCount = static::countProductsWithInstallation($productInOrder) - $salary->count;
                // Если это страница обновления товара
                if (fromUpdatingProductPage() && ProductHelper::oldProductHasInstallation()) {
                    $missingCount -= oldProductsCount();
                }

                $result = $salary->salary + $missingCount * $salary->salary_for_count;
            }

            foreach (static::productsWithInstallation($productInOrder) as $product) {
                // todo пропускать старый товар который еще не удален, колхоз, при рефакторинге избавиться от этого
                if (fromUpdatingProductPage() && oldProduct('id') == $product->id) {
                    continue;
                }

                if (ProductHelper::productHasCoefficient($product)) {
                    $data = ProductHelper::productData($product);

                    $result = Calculator::salaryForDifficulty(
                        salary: $result,
                        price: $data->installationPrice,
                        coefficient: $data->coefficient,
                        count: $product->count
                    );

                }
            }

            return $result;
        }

        /**
         * According to business logic, for calculating salary
         * used installation of type that has max price and salary
         *
         * @param ProductInOrder $productInOrder
         * @return Collection
         * @todo дело в том, что тут используется выборка по максимальной цене монтажа
         * а не зарплаты, поэтому нужно сделать отдельный метод для выборки максимальной зарплаты
         */
        public static function productsWithMaxInstallation(
            ProductInOrder $productInOrder
        ): Collection {
            $typeId = Type::byCategory($productInOrder->category_id)->id;
            $productsWithInstallation = $productInOrder->order
                ->products()
                ->leftJoin(
                    'mosquito_systems_type_additional',
                    'mosquito_systems_type_additional.additional_id',
                    '=',
                    'products.installation_id'
                )
                ->where('mosquito_systems_type_additional.type_id', $typeId)
                // todo оставить только те поля которые нужны
                ->get(['*', 'price as installation_price']);

            $maxInstallationPrice = $productsWithInstallation->max('installation_price');

            return $productsWithInstallation->filter(function ($product) use ($maxInstallationPrice) {
                return $product->installation_price == $maxInstallationPrice;
            });
        }

        /**
         * Get profiles for mosquito systems
         *
         * @param ProductInOrder|null $product
         * @return Collection
         */
        public static function profiles(ProductInOrder $product = null): Collection {
            $productData = null;

            if (!is_null($product)) {
                $productData = json_decode($product->data);
            }

            return Profile::whereHas('products.type', function ($query) use ($product, $productData) {
                return $query->where('category_id', $product->category_id ?? request()->input('categoryId'))
                    ->where('tissue_id', $productData->tissueId ?? request()->input('additional'));
            })
                ->get(['id', 'name']);
        }

        /**
         * Get tissues for mosquito systems
         *
         * @param int $categoryId
         * @return Collection
         */
        public static function tissues(int $categoryId) {
            // todo колхоз
            return \App\Models\Category::tissues($categoryId)
                ->get()
                ->pluck('type')
                ->pluck('products')
                ->collapse()
                ->pluck('tissue')
                ->unique('id');
        }

        /**
         * Getting additional data for mosquito systems
         *
         * @param ProductInOrder|null $productInOrder
         * @return array
         */
        public static function additional(
            ProductInOrder $productInOrder = null
        ): array {
            $productData = null;

            if (isset($productInOrder->data)) {
                $productData = json_decode($productInOrder->data);
            }

            $product = Product::whereTissueId($productData->tissueId ?? request()->input
                ('nextAdditional'))
                ->whereProfileId($productData->profileId ?? request()->input('additional'))
                ->whereHas('type', function ($query) use ($productData) {
                    $query->where('category_id', $productData->category ?? request()->input('categoryId'));
                })->first();

            $additional = $product->additional;

            $groups = Group::whereHas('additional', function ($query) use ($additional) {
                $query->whereIn('id', $additional->pluck('id'));
            })->get()
                // Заполняем для каждой группы выбранное в заказе значение
                ->each(function ($item) use ($productData) {
                    $name = "group-$item->id";
                    if (isset($productData) && $productData->$name !== null) {
                        $item->selected = $productData->$name;
                    }
                });

            return compact('additional', 'groups', 'product');
        }
    }
