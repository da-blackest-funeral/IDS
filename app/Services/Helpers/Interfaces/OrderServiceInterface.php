<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\CreateOrderDto;
    use Illuminate\Support\Collection;

    interface OrderServiceInterface
    {
        /**
         * @param Order $order
         * @return OrderServiceInterface
         */
        public function use(Order $order): OrderServiceInterface;

        /**
         * @param CreateOrderDto $dto
         * @return Order
         */
        public function make(CreateOrderDto $dto): Order;

        /**
         * @return mixed
         */
        public function create(Calculator $calculator, object $requestData): Order;

        /**
         * @return bool
         */
        public function orderOrProductHasInstallation(): bool;

        /**
         * Creates new product and adds it to the order
         *
         * @param Calculator $calculator
         * @param object $requestData
         * @return ProductInOrder
         */
        public function addProduct(Calculator $calculator, object $requestData): ProductInOrder;

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
