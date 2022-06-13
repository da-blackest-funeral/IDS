<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use Illuminate\Support\Collection;

    class InstallationSalaryService
    {
        /**
         * @param Order $order
         */
        public function __construct(
            private Order $order
        ) {}

        /**
         * @param Order $order
         * @return InstallationSalaryService
         */
        public function setOrder(Order $order): self {
            $this->order = $order;
            return $this;
        }

        /**
         * @return void
         */
        public function removeNoInstallation(): void {
            $this->salariesNoInstallation($this->order)
                ->each(function (InstallerSalary $salary) {
                    $this->update(0, $salary);
                });
        }

        /**
         * @param int|float $sum
         * @param InstallerSalary $salary
         */
        public function update(int|float $sum, InstallerSalary $salary) {
            $salary->sum = $sum;
            $salary->update();
        }

        /**
         * @param Order $order
         * @return Collection<InstallerSalary>
         */
        public function salariesNoInstallation(Order $order): Collection {
            return $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get();
        }

        /**
         * @param Order $order
         * @return void
         */
        public function restoreNoInstallation(Order $order) {
            $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get()
                ->each(function (InstallerSalary $salary) use ($order) {
                    $this->update($this->noInstallationSalarySum($order), $salary);
                });
        }

        /**
         * @param Order $order
         * @return int|float
         */
        public function noInstallationSalarySum(Order $order): int|float {
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
        public function hasSalaryNoInstallation(Order $order): bool {
            return $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->where('sum', '>', 0)
                ->exists();
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return \Illuminate\Database\Eloquent\Model
         */
        public function salary(ProductInOrder $productInOrder) {
            return $productInOrder->order
                ->salaries()
                ->where('category_id', $productInOrder->category_id)
                ->firstOrFail();
        }
    }
