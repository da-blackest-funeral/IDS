<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use App\Services\Repositories\Classes\ProductRepository;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    class MosquitoSystemsHelper extends AbstractProductHelper
    {
        /**
         * @return void
         */
        public function updateOrCreateSalary(): void {

            if ($this->needUpdateSalary()) {
                \SalaryHelper::update(
                    sum: $this->calculateInstallationSalary(
                        productInOrder: $this->productsWithMaxInstallation()
                            ->first(),
                        count: ProductRepository::withInstallation($this->order)
                            ->count(),
                    ),
                );

                // todo проверить, нужно ли делать обратное
                // когда убирается монтаж сделать возвращение зарплат за замер и доставку
                if ($this->salariesForNoInstallationMustBeRemoved()) {
                    \SalaryHelper::removeNoInstallation();
                }

                \SalaryHelper::checkMeasuringAndDelivery();

            } else {
                // todo баг
                // когда есть товар другого типа в заказе и меняешь с "нужен монтаж" на без монтажа,
                // зарплата считается криво, при втором обновлении того же товара без изменений все нормализуется
                // todo баг когда в заказе несколько товаров без монтажа и при обновлении одного из них
                //  даже если ничего не поменять то зарплата из 0 становится 960

                // todo когда обновляешь товар без монтажа, которому создается пустая зарплата с суммой 0, и в заказе
                // есть товар другого типа без монтажа, за который есть зп в 960, то зарплата складывается из
                // 960 за один и за монтаж за другой, надо обнулять зарплату которая равна 960

                // todo возможное решение проблемы с зарплатой за доставку и замер - хранить ее прямо в заказе
                // или хранить данные о типе зарплаты, т.е. за что она была начислена, и при необходимости
                // находить такую зарплату по типу и обнулять ее

                if (\OrderHelper::hasProducts() && !Calculator::productNeedInstallation()) {
                    \SalaryHelper::make(0);
                    return;
                }

                \SalaryHelper::make();
            }
        }

        /**
         * @return bool
         */
        protected function salariesForNoInstallationMustBeRemoved(): bool {
            return ! \OrderHelper::hasInstallation() && \OrderHelper::hasProducts() &&
                ! Calculator::productNeedInstallation() &&
                fromUpdatingProductPage() &&
                static::hasInstallation(oldProduct());
        }

        /**
         * @return bool
         */
        protected function needUpdateSalary(): bool {
            $sameCategoryProducts = ProductRepository::byCategory($this->productInOrder)
                ->without($this->productInOrder);

            $countOfAllProducts = ProductRepository::use($this->productInOrder->order->products)
                ->without(oldProduct())
                ->count();

            return $sameCategoryProducts->isNotEmpty() &&
            !is_null(\SalaryHelper::salary()) ||
            !$countOfAllProducts && fromUpdatingProductPage();
        }

        /**
         * @return bool
         */
        protected function needDecreaseCount(): bool {
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

            $result += $this->difficultySalary($productInOrder);

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
         * @todo переименовать т.к. уже есть метод salaryByCount
         */
        protected function salaryForCount(ProductInOrder $productInOrder, int $count, int $typeId) {
            $salary = Calculator::getInstallationSalary(
                installation: $productInOrder->installation_id,
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
                return \OrderHelper::salaries();
            }

            $missingCount = ProductRepository::withInstallation($productInOrder->order)
                    ->count() - $salary->count;
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
        protected function difficultySalary(ProductInOrder $productInOrder) {
            $salary = 0;

            $products = ProductRepository::withInstallation($productInOrder->order)
                ->without(oldProduct());

            foreach ($products->get() as $product) {
                if ($this->productHasCoefficient($product)) {

                    $salary += Calculator::salaryForDifficulty(
                        price: $product->data->installationPrice,
                        coefficient: $product->data->coefficient,
                        count: $product->count
                    );
                }
            }

            return $salary;
        }

        /**
         * @return Collection
         */
        protected function productsWithMaxInstallation(): Collection {
            $typeId = Type::byCategory($this->productInOrder->category_id)->id;
            $productsWithInstallation = $this->order
                ->products()
                ->leftJoin(
                    'mosquito_systems_type_additional',
                    'mosquito_systems_type_additional.additional_id',
                    '=',
                    'products.installation_id'
                )
                ->where('mosquito_systems_type_additional.type_id', $typeId)
                // todo оставить только те поля которые нужны
                ->get();

            $maxInstallationPrice = $productsWithInstallation->max('price');

            return $productsWithInstallation->filter(function ($product) use ($maxInstallationPrice) {
                return equals($product->price, $maxInstallationPrice);
            });
        }

        /**
         * @param ProductInOrder|null $product
         * @return Collection
         */
        public function profiles(ProductInOrder $product = null): Collection {
            return Profile::whereHas('products.type', function ($query) use ($product) {
                return $query->where('category_id', $product->category_id ?? request()->input('categoryId'))
                    ->where('tissue_id', $product->data->tissueId ?? request()->input('additional'));
            })->get(['id', 'name']);
        }

        /**
         * @param int $categoryId
         * @return Collection
         */
        public function tissues(int $categoryId): Collection {
            // todo колхоз, переделать и убрать этот метод из Category
            return \App\Models\Category::tissues($categoryId)
                ->get()
                ->pluck('type')
                ->pluck('products')
                ->collapse()
                ->pluck('tissue')
                ->unique();
        }

        /**
         * @param ProductInOrder|null $productInOrder
         * @return array
         */
        public function additional(ProductInOrder $productInOrder = null): array {
            $product = $this->findProduct($productInOrder);

            $additional = $product->additional;
            $groups = $this->groupsByAdditional($additional);
            $this->fillSelectedGroups($groups);

            return compact('additional', 'groups', 'product');
        }

        /**
         * @param Collection $groups
         * @return void
         */
        protected function fillSelectedGroups(Collection $groups): void {
            $groups->each(function ($item) {
                $name = "group-$item->id";
                if (isset($this->productInOrder->data) && $this->productInOrder->data->$name !== null) {
                    $item->selected = $this->productInOrder->data->$name;
                }
            });
        }

        /**
         * @param Collection $additional
         * @return Collection
         */
        protected function groupsByAdditional(Collection $additional): Collection {
            return Group::whereHas('additional', function ($query) use ($additional) {
                $query->whereIn('id', $additional->pluck('id'));
            })->get();
        }

        /**
         * @param ProductInOrder|null $productInOrder
         * @return Product
         */
        protected function findProduct(ProductInOrder $productInOrder = null): Product {
            return Product::whereTissueId($productInOrder->data->tissueId ?? request()->input('nextAdditional'))
                ->whereProfileId($productInOrder->data->profileId ?? request()->input('additional'))
                ->whereHas('type', function ($query) use ($productInOrder) {
                    $query->where('category_id', $productInOrder->data->category ?? request()->input('categoryId'));
                })->first();
        }
    }
