<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;

    interface DeliveryService
    {
        /**
         * @return mixed
         */
        public function calculateDeliveryOptions();

        /**
         * @return Order
         */
        public function getResultOrder(): Order;
    }
