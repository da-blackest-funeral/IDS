<?php

    namespace App\Services\Visitors\Classes;

    use App\Services\Visitors\Interfaces\Visitable;

    class RemoveDeliveryVisitable implements Visitable
    {
        public function accept() {
            if (\order()->need_delivery) {
                \SalaryHelper::removeDelivery();
                \order()->price -= \order()->delivery;
                \order()->delivery = 0;
            }

            \order()->need_delivery = false;
        }
    }
