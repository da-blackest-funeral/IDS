<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Services\Commands\Interfaces\Command;

    class RemoveDeliveryCommand implements Command
    {
        public function __construct(protected Order $order) {
        }

        public function execute() {
            if ($this->order->need_delivery) {
                \SalaryHelper::removeDelivery();
                $this->order->price -= $this->order->delivery * (1 + $this->order->additional_visits);
                $this->order->delivery = 0;
            }

            $this->order->need_delivery = false;
        }
    }
