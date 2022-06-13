<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
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
                    $this->update(0, $salary);
                });
        }

        /**
         * @param int|float $sum
         * @param InstallerSalary $salary
         * @todo может быть убрать этот метод отсюда и вернуть в старый класс
         */
        public function update(int|float $sum, InstallerSalary $salary) {
            $salary->sum = $sum;
            $salary->update();
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
                    $this->update($this->sum($order), $salary);
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

        /**
         * @param ProductInOrder $productInOrder
         * @return \Illuminate\Database\Eloquent\Model
         *
         * @todo убрать этот метод отсюда
         */
        public function salary(ProductInOrder $productInOrder) {
            return $productInOrder->order
                ->salaries()
                ->where('category_id', $productInOrder->category_id)
                ->firstOrFail();
        }
    }
