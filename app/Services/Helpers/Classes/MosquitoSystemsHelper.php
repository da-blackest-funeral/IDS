<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    // real-time facade

    class MosquitoSystemsHelper extends AbstractProductHelper
    {
        public function updateOrCreateSalary(ProductInOrder $productInOrder) {
            $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id)
                ->get()
                ->reject(function ($product) use ($productInOrder) {
                    return $product->id == $productInOrder->id;
                });

            $count = $this->countProductsWithInstallation($productInOrder);

            $countOfAllProducts = $this->countOfProducts(
                \OrderHelper::withoutOldProduct($productInOrder->order->products)
            );

            $productsWithMaxInstallation = $this->productsWithMaxInstallation($productInOrder);

            /*
             * Need to determine if products of the same type
             * that current exists.
             * Because new product had been already created,
             * we need to skip them
             */
            $productsOfTheSameTypeExists = $products->isNotEmpty();

            if (
                $productsOfTheSameTypeExists &&
                !is_null(\SalaryHelper::salary($productInOrder)) || !$countOfAllProducts && fromUpdatingProductPage()
            ) {
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

                \SalaryHelper::update(
                    sum: $this->calculateInstallationSalary(
                        productInOrder: $productsWithMaxInstallation->first(),
                        count: $count,
                    ),
                    productInOrder: $productInOrder,
                );

            } else {
                // todo учесть если зарплата создается за монтаж товара другого типа, чтобы не создавалась
                //  зарплата за доставку и монтаж
                // todo баг
                // когда есть товар другого типа в заказе и меняешь с "нужен монтаж" на без монтажа,
                // зарплата считается криво, при втором обновлении того же товара без изменений все нормализуется
//                if () {
                    \SalaryHelper::make($productInOrder->order);
//                }
            }
        }

        public function calculateInstallationSalary(
            ProductInOrder $productInOrder,
            int            $count,
                           $installation = null
        ): int {

            if (fromUpdatingProductPage() && oldProductHasInstallation()) {
                $count -= oldProductsCount();
            }

            $result = 0;

            $salary = Calculator::getInstallationSalary(
                installation: $installation ?? $productInOrder->installation_id,
                count: $count,
                /*
                 * todo исправить
                 * вытаскивать typeId по $productInOrder->category_id
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
                    return orderSalaries($productInOrder->order);
                }

                $missingCount = $this->countProductsWithInstallation($productInOrder) - $salary->count;
                // Если это страница обновления товара
                if (fromUpdatingProductPage() && oldProductHasInstallation()) {
                    $missingCount -= oldProductsCount();
                }

                $result = $salary->salary + $missingCount * $salary->salary_for_count;
            }

            foreach ($this->productsWithInstallation($productInOrder) as $product) {
                // todo пропускать старый товар который еще не удален, колхоз, при рефакторинге избавиться от этого
                if (fromUpdatingProductPage() && oldProduct('id') == $product->id) {
                    continue;
                }

                if ($this->productHasCoefficient($product)) {
                    $data = $this->productData($product);

                    // todo баг с этим
                    // тут возвращается null, сделать как в прошлый раз я суммировал
                    // доп з\п за сложность
                    $result = Calculator::salaryForDifficulty(
                        price: $data->installationPrice,
                        coefficient: $data->coefficient,
                        count: $product->count
                    );

                }
            }

            return $result ?? 0;
        }

        public function countOfProducts(Collection $products) {
            return $products->sum('count');
        }

        public function productHasCoefficient(ProductInOrder $productInOrder) {
            return $this->productData($productInOrder, 'coefficient') > 1;
        }

        public function productData(ProductInOrder $productInOrder, string $field = null) {
            if (is_null($field)) {
                return json_decode($productInOrder->data);
            }

            return json_decode($productInOrder->data)->$field;
        }

        public function productsWithMaxInstallation(ProductInOrder $productInOrder) {
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

        public function countProductsWithInstallation(ProductInOrder $productInOrder): int {
            return $this->countOfProducts(
                $this->productsWithInstallation($productInOrder)
            );
        }

        /*
         * todo улучшение кода
         * когда буду рефакторить, надо сделать так, чтобы пропускался старый товар (при обновлении, который еще не удален)
         * во всех местах где используется этот метод нужно это учесть
         */
        public function productsWithInstallation(ProductInOrder $productInOrder): Collection {
            return $productInOrder->order
                ->products()
                ->where('category_id', request()->input('categories'))
                ->whereNotIn('installation_id', [0, 14])
                ->get();
        }

        public function profiles(ProductInOrder $product = null): Collection {
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

        public function tissues(int $categoryId) {
            // todo колхоз
            return \App\Models\Category::tissues($categoryId)
                ->get()
                ->pluck('type')
                ->pluck('products')
                ->collapse()
                ->pluck('tissue')
                ->unique();
        }

        public function additional(ProductInOrder $productInOrder = null) {
            $productData = null;

            if (isset($productInOrder->data)) {
                $productData = json_decode($productInOrder->data);
            }

            $product = Product::whereTissueId($productData->tissueId ?? request()->input('nextAdditional'))
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
