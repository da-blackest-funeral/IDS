<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    class RemoveDeliveryCommand implements Command
    {
        public function __construct(
            private readonly Order $order,
            private readonly InstallerSalary $salary
        ) {
        }

        public function execute() {
            if (!$this->order->need_delivery) {
                return;
            }

            \SalaryService::removeDelivery($this->salary);
            $this->order->price -= $this->order->delivery * (1 + $this->order->additional_visits);
            $this->order->delivery = 0;

            $this->order->need_delivery = false;
        }
    }
