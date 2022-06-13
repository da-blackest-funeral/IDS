<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Services\Commands\Interfaces\Command;
    use App\Services\Repositories\Interfaces\ProductRepository;

    class RestoreDeliveryCommand implements Command
    {
        /**
         * @param Order $order
         * @param ProductRepository $productRepository
         */
        public function __construct(
            protected Order $order,
            protected ProductRepository $productRepository
        ) {
        }

        public function execute() {
            if ($this->order->need_delivery) {
                return;
            }

            $delivery = $this->productRepository
                ->maxDelivery();

            \SalaryService::restoreDelivery();
            $this->order->price += $delivery * (1 + $this->order->additional_visits);
            $this->order->delivery = $delivery;

            $this->order->need_delivery = true;
        }
    }
