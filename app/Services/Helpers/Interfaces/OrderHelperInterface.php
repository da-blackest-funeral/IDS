<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface OrderHelperInterface
    {
        public function make();

        public function orderOrProductHasInstallation(Order $order);

        /**
         * Creates new product and adds it to the order
         *
         * @param Order $order
         * @return ProductInOrder
         */
        public function addProductTo(Order $order): ProductInOrder;

        /**
         * Calculates salary for all order
         *
         * @param Order $order
         * @return float
         */
        public function salaries(Order $order): float;

        /**
         * @param Order $order
         * @return bool
         */
        public function hasInstallation(Order $order): bool;

        /**
         * @param Order $order
         * @return bool
         */
        public function hasProducts(Order $order): bool;

        /**
         * @param Collection $products
         * @return Collection
         */
        public function withoutOldProduct(Collection $products): Collection;
    }
