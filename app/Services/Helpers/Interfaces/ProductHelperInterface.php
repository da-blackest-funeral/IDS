<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface ProductHelperInterface
    {
        /**
         * @return void
         */
        public function updateOrCreateSalary(): void;

        /**
         * @param ProductInOrder $productInOrder
         * @return ProductHelperInterface
         */
        public function use(ProductInOrder $productInOrder): ProductHelperInterface;

        /**
         * Calculates salary for specified product and count
         *
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @return int
         */
        public function calculateInstallationSalary(ProductInOrder $productInOrder, int $count): int;

        /**
         * Determines if product has coefficient difficulty
         *
         * @param ProductInOrder $productInOrder
         * @return bool
         */
        public function productHasCoefficient(ProductInOrder $productInOrder): bool;

        /**
         * Getting second select data for ajax displaying
         *
         * @param ProductInOrder|null $product
         * @return Collection
         */
        public function profiles(ProductInOrder $product = null): Collection;

        /**
         * Getting third select data for ajax displaying
         *
         * @param int $categoryId
         * @return Collection
         */
        public function tissues(int $categoryId): Collection;

        /**
         * Getting last selects for ajax
         *
         * @param ProductInOrder|null $productInOrder
         * @return array
         */
        public function additional(ProductInOrder $productInOrder = null): array;
    }
