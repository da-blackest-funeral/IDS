<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Services\Commands\Interfaces\Command;
    use App\Services\Repositories\Interfaces\ProductRepositoryInterface;

    class RestoreDeliveryCommand implements Command
    {
        public function __construct(
            protected Order $order,
            protected ProductRepositoryInterface $productRepository
        ) {}

        public function execute() {
            /** @var int $delivery */
            $delivery = $this->productRepository
                ->maxDelivery();

            if (!$this->order->need_delivery) {
                \SalaryHelper::restoreDelivery();
                $this->order->price += $delivery;
                $this->order->delivery = $delivery;
            }

            $this->order->need_delivery = true;
        }
    }
