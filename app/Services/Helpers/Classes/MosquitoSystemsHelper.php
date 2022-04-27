<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\Interfaces\ProductHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    class MosquitoSystemsHelper extends AbstractProductHelper implements ProductHelperInterface
    {
        public function updateOrCreateSalary(ProductInOrder $productInOrder) {
            $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id)
                ->get()
                ->reject(function ($product) use ($productInOrder) {
                    return $product->id == $productInOrder->id;
                });

            $count = $this->countProductsWithInstallation($productInOrder);

            $countOfAllProducts = $this->countOf(
                \OrderHelper::withoutOldProduct($productInOrder->order->products)
            );

            $productsWithMaxInstallation = $this->productsWithMaxInstallation($productInOrder);

            if (
                /*
                 * Need to determine if products of the same type
                 * that current exists.
                 * Because new product had been already created,
                 * we need to skip them
                 */
                $products->isNotEmpty() &&
                !is_null(\SalaryHelper::salary($productInOrder)) ||
                !$countOfAllProducts && fromUpdatingProductPage()
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

                \SalaryHelper::checkMeasuringAndDelivery(
                    order: $productInOrder->order,
                    productInOrder: $productInOrder
                );

            } else {
                // todo баг
                // когда есть товар другого типа в заказе и меняешь с "нужен монтаж" на без монтажа,
                // зарплата считается криво, при втором обновлении того же товара без изменений все нормализуется
                // todo баг когда в заказе несколько товаров без монтажа и при обновлении одного из них
                //  даже если ничего не поменять то зарплата из 0 становится 960

                // todo когда обновляешь товар без монтажа, которому создается пустая зарплата с суммой 0, и в заказе
                // есть товар другого типа без монтажа, за который есть зп в 960, то зарплата складывается из
                // 960 за один и за монтаж за другой, надо обнулять зарплату которая равна 960

                /*
                 * условие: если в заказе есть товары с монтажом и нынешнему товару не нужен монтаж, то не создавать зп
                 */
                if (\OrderHelper::hasProducts($productInOrder->order) && !Calculator::productNeedInstallation()) {
                    \SalaryHelper::make($productInOrder->order, 0);
                    return;
                }

                \SalaryHelper::make($productInOrder->order);
            }
        }

        protected function needDecreaseCount() {
            return fromUpdatingProductPage() && oldProductHasInstallation();
        }

        /**
         * Calculates salary for specified product and count
         *
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @return int
         */
        public function calculateInstallationSalary(ProductInOrder $productInOrder, int $count): int {
            if ($this->needDecreaseCount()) {
                $count -= oldProductsCount();
            }

            $result = $this->salaryByCount(
                productInOrder: $productInOrder,
                count: $count,
                typeId: Type::byCategory($productInOrder->category_id)->id
            );

            $result += $this->checkDifficultySalary($productInOrder);

            return $result;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @param int $typeId
         * @return float|int|mixed
         */
        protected function salaryByCount(ProductInOrder $productInOrder, int $count, int $typeId) {
            try {
                $result = $this->salaryForCount(
                    productInOrder: $productInOrder,
                    count: $count,
                    typeId: $typeId
                );
            } catch (\Exception) {
                $result = $this->salaryForMaxCount(
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
        protected function salaryForCount(ProductInOrder $productInOrder, int $count, int $typeId) {
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
        protected function salaryForMaxCount(ProductInOrder $productInOrder, int $typeId) {
            $salary = Calculator::maxCountSalary(
                installation: $productInOrder->installation_id,
                typeId: $typeId
            );

            if (is_null($salary)) {
                return \OrderHelper::salaries($productInOrder->order);
            }

            $missingCount = $this->countProductsWithInstallation($productInOrder) - $salary->count;
            if ($this->needDecreaseCount()) {
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
        protected function checkDifficultySalary(ProductInOrder $productInOrder) {
            $salary = 0;

            $products = \OrderHelper::withoutOldProduct(
                $this->productsWithInstallation($productInOrder)
            );

            foreach ($products as $product) {
                if ($this->productHasCoefficient($product)) {
                    $data = $this->productData($product);

                    $salary += Calculator::salaryForDifficulty(
                        price: $data->installationPrice,
                        coefficient: $data->coefficient,
                        count: $product->count
                    );
                }
            }

            return $salary;
        }

        public function countOf(Collection $products) {
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
            return $this->countOf(
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
