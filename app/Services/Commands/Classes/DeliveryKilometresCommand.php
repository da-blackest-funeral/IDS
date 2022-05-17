<?php

    namespace App\Services\Commands\Classes;

    class DeliveryKilometresCommand extends DeliveryCommand
    {
        public function execute() {
            $this->order->price += $this->deliveryPrice * ($this->kilometres - order()->kilometres);
            $this->salary->sum += $this->deliveryWage * ($this->kilometres - order()->kilometres);

            $this->order->kilometres = $this->kilometres;

            $this->salary->update();
            $this->order->update();
        }
    }
