<?php

    namespace App\Services\Commands\Classes;

    use App\Services\Commands\Interfaces\Command;

    class RemoveDeliveryCommand implements Command
    {
        public function execute() {
            if (\order()->need_delivery) {
                \SalaryHelper::removeDelivery();
                \order()->price -= \order()->delivery;
                \order()->delivery = 0;
            }

            \order()->need_delivery = false;
        }
    }
