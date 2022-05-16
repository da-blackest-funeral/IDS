<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    class RemoveAdditionalVisitsCommand implements Command
    {
        public function __construct(
            protected Order $order
        ) {}

        public function execute() {
            /** @var InstallerSalary $salary */
            $salary = \SalaryHelper::salariesNoInstallation($this->order)
                ->first();
            $salary->sum -= systemVariable('delivery') * $this->order->additional_visits;
            $salary->update();

            $this->order->price -= $this->order->delivery * $this->order->additional_visits;
            $this->order->additional_visits = 0;
        }
    }
