<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    // todo добавить в команды метод save
    class SetAdditionalVisitsCommand implements Command
    {
        public function __construct(
            protected Order $order,
            protected InstallerSalary $salary,
            protected int $visits
        ) {}

        public function execute() {
            if ($this->order->additional_visits < $this->visits) {
                $this->salary->sum += systemVariable('delivery') * $this->visits;
                $this->order->price += $this->order->delivery * $this->visits;
            }

            $this->order->additional_visits = $this->visits;

            return $this;
        }

        public function save() {
            $this->order->update();
            $this->salary->update();
        }
    }
