<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    class RemoveAdditionalVisitsCommand implements Command
    {
        /**
         * @param Order $order
         * @param InstallerSalary $salary
         */
        public function __construct(
            private readonly Order $order,
            private readonly InstallerSalary $salary
        ) {
        }

        public function execute() {
            if (!$this->order->additional_visits) {
                return;
            }

            $this->salary->sum -= systemVariable('delivery') * $this->order->additional_visits;

            $this->order->price -= $this->order->delivery * $this->order->additional_visits;
            $this->order->additional_visits = 0;
        }
    }
