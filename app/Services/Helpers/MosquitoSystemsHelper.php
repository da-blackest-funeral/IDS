<?php

    namespace App\Services\Helpers;

    use App\Models\Category;
    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    /*
     * todo сделать интерфейс
     * в сервис провайдере биндить в зависимости от категорий,
     * и таким образом получится универсальные классы хелперов
     *
     * аналогично можно поступить с классами SalaryHelper и OrderHelper,
     * т.к. могут возникнуть проблемы с универсальностью
     */

    class MosquitoSystemsHelper extends ProductHelper
    {
        /**
         * This method determines the logic of
         * how to make salary for new mosquito system product.
         *
         * @param ProductInOrder $productInOrder
         * @return void
         */
        public static function updateOrCreateSalary(ProductInOrder $productInOrder) {
            $products = static::sameCategoryProducts($productInOrder);
            $productsWithMaxInstallation = static::productsWithMaxInstallation($productInOrder);
            $order = $productInOrder->order;

            $countWithInstallation = static::countProductsWithInstallation($productInOrder);
            $countOfAllProducts = static::countOfProducts(OrderHelper::products($order));

            /*
             * Need to determine if products of the same type
             * that current exists.
             * Because new product had been already created,
             * we need to skip them
             */
            if (static::needUpdateSalary($products, $productInOrder)) {
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
                        count: $countWithInstallation,
                    ),
                    productInOrder: $productInOrder,
                );

            } elseif (!$countOfAllProducts || $countWithInstallation) {
                SalaryHelper::make($order);
            }
        }

        protected static function needDecreaseCount() {
            return fromUpdatingProductPage() && ProductHelper::oldProductHasInstallation();
        }

        /**
         * Calculates salary for specified product and count
         *
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @return int
         */
        public static function calculateInstallationSalary(ProductInOrder $productInOrder, int $count): int {
            if (static::needDecreaseCount()) {
                $count -= oldProductsCount();
            }

            $result = static::salaryByCount(
                productInOrder: $productInOrder,
                count: $count,
                typeId: Type::byCategory($productInOrder->category_id)->id
            );

            $result += static::checkDifficultySalary($productInOrder);

            return $result;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @param int $typeId
         * @return float|int|mixed
         */
        protected static function salaryByCount(ProductInOrder $productInOrder, int $count, int $typeId) {
            try {
                $result = static::salaryForCount(
                    productInOrder: $productInOrder,
                    count: $count,
                    typeId: $typeId
                );
            } catch (\Exception) {
                $result = static::salaryForMaxCount(
                    productInOrder: $productInOrder,
                    typeId: $typeId
                );
            }

            return $result;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @param int $typeId
         * @return mixed
         */
        protected static function salaryForCount(ProductInOrder $productInOrder, int $count, int $typeId) {
            $salary = Calculator::getInstallationSalary(
                installation: $installation ?? $productInOrder->installation_id,
                count: $count,
                typeId: $typeId
            );

            return $salary->salary;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @param int $typeId
         * @return float|int
         */
        protected static function salaryForMaxCount(ProductInOrder $productInOrder, int $typeId) {
            $salary = Calculator::maxCountSalary(
                installation: $productInOrder->installation_id,
                typeId: $typeId
            );

            if (is_null($salary)) {
                return OrderHelper::salaries($productInOrder->order);
            }

            $missingCount = static::countProductsWithInstallation($productInOrder) - $salary->count;
            if (static::needDecreaseCount()) {
                $missingCount -= oldProductsCount();
            }

            return (int)(
                $salary->salary + $missingCount * $salary->salary_for_count
            );
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return int
         */
        protected static function checkDifficultySalary(ProductInOrder $productInOrder) {
            $salary = 0;

            $products = OrderHelper::withoutOldProduct(
                static::productsWithInstallation($productInOrder)
            );

            foreach ($products as $product) {
                if (static::productHasCoefficient($product)) {
                    $data = static::productData($product);

                    $salary += Calculator::salaryForDifficulty(
                        price: $data->installationPrice,
                        coefficient: $data->coefficient,
                        count: $product->count
                    );
                }
            }

            return $salary;
        }

        /**
         * According to business logic, for calculating salary
         * used installation of type that has max price and salary
         *
         * @param ProductInOrder $productInOrder
         * @return Collection
         */
        protected static function productsWithMaxInstallation(ProductInOrder $productInOrder): Collection {
            $productsWithInstallation = static::joinInstallation(
                productInOrder: $productInOrder,
                typeId: Type::byCategory($productInOrder->category_id)->id
            );

            $maxInstallationPrice = $productsWithInstallation->max('installation_price');

            return $productsWithInstallation->filter(function ($product) use ($maxInstallationPrice) {
                return $product->installation_price == $maxInstallationPrice;
            });
        }

        /**
         * Logic of joining installation to products
         * by product's installation_id
         *
         * @param ProductInOrder $productInOrder
         * @param int $typeId
         * @return Collection
         */
        protected static function joinInstallation(ProductInOrder $productInOrder, int $typeId): Collection {
            return $productInOrder->order
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
        }

        /**
         * Get profiles for mosquito systems
         *
         * @param ProductInOrder|null $productInOrder
         * @return Collection
         */
        public static function profiles(ProductInOrder $productInOrder = null): Collection {
            $productData = new \stdClass();
            if (!is_null($productInOrder)) {
                $productData = static::productData($productInOrder);
            }

            return Profile::whereHas(
                'products.type',
                function ($query) use ($productInOrder, $productData) {
                    return $query->where(
                        'category_id',
                        $productInOrder->category_id ?? request()->input('categoryId')
                    )->where(
                        'tissue_id',
                        $productData->tissueId ?? request()->input('additional')
                    );
                }
            )->get(['id', 'name']);
        }

        /**
         * Get tissues for mosquito systems
         *
         * @param int $categoryId
         * @return Collection
         */
        public static function tissues(int $categoryId) {
            /* todo
             *  Метод scopeTissues больше нигде не используется, можно по нормальному переписать логику
             *  выборки полотна
             */

            /*
             * Category::find($id)
             *     ->type()
             *     ->products()
             *     ->tissue()
             *     ->get(['id', 'name'])
             *     ->unique('id');
             */

            return Category::tissues($categoryId)
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
         */
        public static function additional(ProductInOrder $productInOrder = null) {
            $productData = new \stdClass();
            if (!is_null($productInOrder)) {
                $productData = static::productData($productInOrder);
            }

            try {
                $product = static::product($productData);
            } catch (\Exception) {
                return back()->withErrors([
                    'not_found' => 'Товар не найден',
                ]);
            }

            $additional = $product->additional;

            $groups = static::groupsByAdditional($additional, $productData);

            return compact('additional', 'groups', 'product');
        }

        /**
         * Gets groups by additional items
         *
         * @param Collection $additional
         * @param array $productData
         * @return Collection
         */
        protected static function groupsByAdditional(Collection $additional, object $productData): Collection {
            return Group::whereHas('additional', function ($query) use ($additional) {
                $query->whereIn('id', $additional->pluck('id'));
            })->get()
                // Заполняем для каждой группы выбранное в заказе значение
                ->each(function ($item) use ($productData) {
                    $name = "group-$item->id";
                    if (isset($productData, $productData->$name)) {
                        $item->selected = $productData->$name;
                    }
                });
        }

        /**
         * Gets product by tissue, type and profile
         *
         * @param object $productData
         * @return Product|null
         */
        protected static function product(object $productData) {
            return Product::whereTissueId(
                $productData->tissueId ?? request()->input('nextAdditional')
            )->whereProfileId(
                $productData->profileId ?? request()->input('additional')
            )->whereHas('type', function ($query) use ($productData) {
                $query->where('category_id', $productData->category ?? request()->input('categoryId'));
            })->firstOrFail();
        }

        /**
         * Determines if salary need to be updated,
         * or needs to create new salary
         *
         * @param Collection $products
         * @param ProductInOrder $productInOrder
         * @return bool
         */
        protected static function needUpdateSalary(Collection $products, ProductInOrder $productInOrder): bool {
            return OrderHelper::productsWithout(
                    products: $products,
                    productInOrder: $productInOrder
                )->isNotEmpty() &&
                !is_null(SalaryHelper::getSalary($productInOrder));
        }

        /**
         * Get products of the same type from
         * order, to which the product belongs
         *
         * @param ProductInOrder $productInOrder
         * @return Collection
         */
        protected static function sameCategoryProducts(ProductInOrder $productInOrder): Collection {
            return OrderHelper::withoutOldProduct(
                ProductInOrder::whereCategoryId($productInOrder->category_id)
                    ->whereOrderId($productInOrder->order_id)
                    ->get()
            );
        }
    }
