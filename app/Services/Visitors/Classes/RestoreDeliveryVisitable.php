<?php

    namespace App\Services\Visitors\Classes;

    use App\Services\Visitors\Interfaces\Visitable;

    class RestoreDeliveryVisitable implements Visitable
    {
        public function accept() {
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
