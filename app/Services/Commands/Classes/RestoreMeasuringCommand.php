<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    class RestoreMeasuringCommand implements Command
    {
        /**
         * @param Order $order
         * @param InstallerSalary $salary
         */
        public function __construct(
            private readonly Order $order,
            private readonly InstallerSalary $salary,
        ) {}

        public function execute() {
            if ($this->order->measuring) {
                return;
            }

            $this->order->measuring_price = $this->getMeasuringPrice();
            $this->order->price += $this->order->measuring_price;
            $this->salary->sum += $this->getMeasuringSalary();
            $this->order->measuring = true;
        }

        private function getMeasuringPrice() {
            return systemVariable('measuring');
        }

        private function getMeasuringSalary() {
            return systemVariable('measuringWage');
        }
    }
