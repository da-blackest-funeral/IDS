<?php

    namespace App\Services\Helpers\Classes;

    use App\Exceptions\SalaryCalculationException;
    use App\Models\Category;
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
         * @throws SalaryCalculationException
         */
        public function updateOrCreateSalary(): void {

            if ($this->needUpdateSalary()) {

                $this->updateSalary();
                $this->checkRemoveNoInstallationSalary();
                \SalaryHelper::checkMeasuringAndDelivery();
            } elseif (! deletingProduct()) {

                if ($this->checkEmptySalary()) {
                    return;
                }

                $this->checkRemoveNoInstallationSalary();

                \SalaryHelper::make();
                return;
            }

            // тут идет код с удалением товара
            $this->checkRestoreNoInstallationSalaries();
        }

        /**
         * @return bool
         */
        protected function checkEmptySalary(): bool {
            if (\OrderHelper::hasProducts() && !Calculator::productNeedInstallation()) {
                \SalaryHelper::make(0);
                return true;
            }

            return false;
        }

        /**
         * @return void
         */
        protected function checkRemoveNoInstallationSalary(): void {
            if ($this->salariesForNoInstallationMustBeRemoved()) {
                \SalaryHelper::removeNoInstallation();
            }
        }

        /**
         * @return void
         * @throws SalaryCalculationException
         */
        protected function updateSalary(): void {
            \SalaryHelper::update(
                sum: $this->calculateInstallationSalary(
                    productInOrder: $this->productsWithMaxInstallation()
                        ->first(),
                    count: ProductRepository::withInstallation($this->order, $this->productInOrder->category_id)
                        ->count(),
                ),
            );
        }

        /**
         * @return void
         */
        protected function checkRestoreNoInstallationSalaries(): void {
            $productsWithInstallation = ProductRepository::use($this->products)
                ->without(oldProduct())
                ->onlyWithInstallation();

            if ($productsWithInstallation->isEmpty()) {
                \SalaryHelper::restoreNoInstallation();
            }
        }

        /**
         * @return bool
         */
        protected function salariesForNoInstallationMustBeRemoved(): bool {
            // todo условие можно сократить
            return
                !\OrderHelper::hasInstallation() &&
                \OrderHelper::hasProducts() &&
                !Calculator::productNeedInstallation() ||
                \OrderHelper::hasInstallation() ||
                Calculator::productNeedInstallation();
        }

        /**
         * @return bool
         */
        protected function needUpdateSalary(): bool {
            // todo возможно, тут не учитывается факт что товары удалены, сделать whereNull('deleted_at')
            $sameCategoryProducts = ProductRepository::byCategoryWithout($this->productInOrder);

            $countOfAllProducts = ProductRepository::use($this->products)
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
            return (fromUpdatingProductPage() || deletingProduct())
                && $this->hasInstallation(oldProduct());
        }

        /**
         * Calculates salary for specified product and count
         *
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @return int
         * @throws SalaryCalculationException
         */
        public function calculateInstallationSalary(ProductInOrder $productInOrder, int $count): int {
            if ($this->needDecreaseCount()) {
                $count -= oldProductsCount();
            }

            $result = $this->salary(
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
         * @throws SalaryCalculationException
         */
        protected function salary(ProductInOrder $productInOrder, int $count, int $typeId) {
            try {
                $result = $this->salaryForCount(
                    productInOrder: $productInOrder,
                    count: $count,
                    typeId: $typeId
                );
            } catch (\Exception $exception) {
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
         * @throws SalaryCalculationException
         */
        protected function salaryForMaxCount(ProductInOrder $productInOrder, int $typeId) {
            $salary = Calculator::maxCountSalary(
                installation: $productInOrder->installation_id,
                typeId: $typeId
            );

            if (is_null($salary)) {
                throw new SalaryCalculationException('Зарплата за данный тип не найдена!');
            }

            $missingCount = ProductRepository::withInstallation($productInOrder->order)
                    ->count() - $salary->count;
            if ($this->needDecreaseCount()) {
                $missingCount -= oldProductsCount();
            }

            return (int)ceil(
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
                ->without(oldProduct())
                ->get();

            foreach ($products as $product) {
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
                )->where('mosquito_systems_type_additional.type_id', $typeId)
                ->get([
                    'category_id',
                    'order_id',
                    'price as installation_price',
                    'products.id as id',
                    'mosquito_systems_type_additional.additional_id as installation_id',
                ]);

            $productsWithInstallation =
                ProductRepository::reject($productsWithInstallation, oldProduct());

            $maxInstallationPrice = $productsWithInstallation->max('installation_price');

            return $productsWithInstallation->filter(function ($product) use ($maxInstallationPrice) {
                return equals($product->installation_price, $maxInstallationPrice);
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
            return Category::findOrFail($categoryId)->type
                ->products()
                ->with('tissue', function ($query) {
                    $query->select(['id', 'name'])->distinct();
                })
                ->get(['tissue_id'])
                ->pluck('tissue');
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

        public function installationCondition(): Callable {
            return function ($product) {
                return \ProductHelper::hasInstallation($product);
            };
        }
    }
