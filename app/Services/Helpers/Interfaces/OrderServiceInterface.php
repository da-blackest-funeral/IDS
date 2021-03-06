<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface OrderServiceInterface
    {
        /**
         * @param Order $order
         * @return OrderServiceInterface
         */
        public function use(Order $order): OrderServiceInterface;

        /**
         * @return Order
         */
        public function make(): Order;

        /**
         * @return bool
         */
        public function orderOrProductHasInstallation(): bool;

        /**
         * Creates new product and adds it to the order
         *
         * @return ProductInOrder
         */
        public function addProduct(): ProductInOrder;

        /**
         * Calculates salary for all order
         *
         * @return float
         */
        public function salaries(): float;

        /**
         * @return bool
         */
        public function hasInstallation(): bool;

        /**
         * @return bool
         */
        public function hasProducts(): bool;
    }
