<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface ProductHelperInterface
    {
        public function updateOrCreateSalary(ProductInOrder $productInOrder);

        /**
         * Calculates salary for specified product and count
         *
         * @param ProductInOrder $productInOrder
         * @param int $count
         * @return int
         */
        public function calculateInstallationSalary(ProductInOrder $productInOrder, int $count): int;

        /**
         * Calculates count by collection of products
         *
         * @param Collection $products
         * @return int
         */
        public function countOf(Collection $products): int;

        /**
         * Determines if product has coefficient difficulty
         *
         * @param ProductInOrder $productInOrder
         * @return bool
         */
        public function productHasCoefficient(ProductInOrder $productInOrder): bool;

        /**
         * Calculates count of products that need installation
         *
         * @param ProductInOrder $productInOrder
         * @return int
         */
        public function countProductsWithInstallation(ProductInOrder $productInOrder): int;

        /**
         * Getting second select data for ajax displaying
         *
         * @param ProductInOrder|null $product
         * @return Collection
         * @todo rename to secondSelect()
         */
        public function profiles(ProductInOrder $product = null): Collection;

        /**
         * Getting third select data for ajax displaying
         *
         * @param int $categoryId
         * @return Collection
         * @todo rename to thirdSelect()
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
