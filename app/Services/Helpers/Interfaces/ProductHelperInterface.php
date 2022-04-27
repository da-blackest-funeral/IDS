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

        public function countOf(Collection $products);

        public function productHasCoefficient(ProductInOrder $productInOrder);

        public function productData(ProductInOrder $productInOrder, string $field = null);

        public function productsWithMaxInstallation(ProductInOrder $productInOrder);

        public function countProductsWithInstallation(ProductInOrder $productInOrder): int;

        public function productsWithInstallation(ProductInOrder $productInOrder): Collection;

        public function profiles(ProductInOrder $product = null): Collection;

        public function tissues(int $categoryId);

        public function additional(ProductInOrder $productInOrder = null);
    }
