<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    class SetAdditionalVisitsCommand implements Command
    {
        public function __construct(
            protected Order $order,
            protected int $visits
        ) {}

        public function execute() {
            /** @var InstallerSalary $salary */
            $salary = \SalaryHelper::salariesNoInstallation($this->order)
                ->first();
            if ($this->order->additional_visits < $this->visits) {
                $salary->sum += systemVariable('delivery') * $this->visits;
                $salary->update();
                $this->order->price += $this->order->delivery * $this->visits;
            }

            $this->order->additional_visits = $this->visits;
        }
    }
