<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;

    class SetDeliveryKilometresCommand extends DeliveryCommand
    {
        public function execute() {
            $this->order->price += $this->deliveryPrice * $this->kilometres;
            $this->salary->sum += $this->deliveryWage * $this->kilometres;

            $this->order->kilometres = $this->kilometres;
        }
    }
