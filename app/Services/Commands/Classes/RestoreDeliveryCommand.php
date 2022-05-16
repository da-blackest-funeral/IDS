<?php

    namespace App\Services\Commands\Classes;

    use App\Services\Commands\Interfaces\Command;

    class RestoreDeliveryCommand implements Command
    {
        public function execute() {
            $delivery = \OrderHelper::getProductRepository()
                ->maxDelivery();

            if (!\order()->need_delivery) {
                \SalaryHelper::restoreDelivery();
                \order()->price += $delivery;
                \order()->delivery = $delivery;
            }

            \order()->need_delivery = true;
        }
    }
