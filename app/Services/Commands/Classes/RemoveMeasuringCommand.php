<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    class RemoveMeasuringCommand implements Command
    {
        public function __construct(
            private readonly Order $order,
            private readonly int $measuringPrice,
            private readonly InstallerSalary $salary
        ) {}

        public function execute() {
            if (!$this->order->measuring) {
                return;
            }

            $this->order->price -= $this->measuringPrice;
            $this->order->measuring_price = 0;
            $this->order->measuring = 0;

            $this->salary->sum -= $this->getMeasuringSalary();
            $this->salary->update();
        }

        private function getMeasuringSalary() {
            return systemVariable('measuringWage');
        }
    }
