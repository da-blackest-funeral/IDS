<?php

    namespace App\Services\Commands\Classes;

    class RemoveDeliveryKilometresCommand extends DeliveryCommand
    {
        public function execute() {
            $this->order->price -= $this->kilometres * $this->deliveryPrice;
            $this->salary->sum -= $this->deliveryWage * $this->kilometres;

            $this->order->kilometres = 0;
        }
    }
