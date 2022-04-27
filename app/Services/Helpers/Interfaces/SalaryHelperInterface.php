<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;
    use App\Models\ProductInOrder;

    interface SalaryHelperInterface
    {
        /**
         * @param int|float $sum
         * @param ProductInOrder $productInOrder
         * @return mixed
         */
        public function update(int|float $sum, ProductInOrder $productInOrder);

        /**
         * @param Order $order
         * @param $sum
         * @return mixed
         */
        public function make(Order $order, $sum = null);

        /**
         * @param ProductInOrder $productInOrder
         * @return mixed
         */
        public function salary(ProductInOrder $productInOrder);

        /**
         * @param Order $order
         * @param ProductInOrder $productInOrder
         * @return mixed
         */
        function checkMeasuringAndDelivery(Order $order, ProductInOrder $productInOrder);
    }
