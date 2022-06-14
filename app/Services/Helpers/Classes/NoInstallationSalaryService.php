<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use Illuminate\Support\Collection;

    class NoInstallationSalaryService
    {
        /**
         * @param Order $order
         */
        public function __construct(
            private Order $order
        ) {}

        /**
         * @param Order $order
         * @return NoInstallationSalaryService
         */
        public function setOrder(Order $order): self {
            $this->order = $order;
            return $this;
        }

        /**
         * @return void
         */
        public function removeAll(): void {
            $this->salaries($this->order)
                ->each(function (InstallerSalary $salary) {
                    \SalaryService::update(salary: $salary, sum: 0);
                });
        }

        /**
         * @param Order $order
         * @return Collection<InstallerSalary>
         */
        public function salaries(Order $order): Collection {
            return $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get();
        }

        /**
         * @param Order $order
         * @return void
         */
        public function restore(Order $order) {
            $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get()
                ->each(function (InstallerSalary $salary) use ($order) {
                    \SalaryService::update(
                        salary: $salary,
                        sum: $this->sum($order)
                    );
                });
        }

        /**
         * @param Order $order
         * @return int|float
         */
        public function sum(Order $order): int|float {
            $result = 0;
            if ($order->measuring_price || $order->measuring) {
                $result += SystemVariables::value('measuringWage');
            }

            if ($order->delivery) {
                $result += SystemVariables::value('delivery');
            }

            return $result;
        }

        /**
         * @param Order $order
         * @return bool
         */
        public function hasSalary(Order $order): bool {
            return $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->where('sum', '>', 0)
                ->exists();
        }
    }
