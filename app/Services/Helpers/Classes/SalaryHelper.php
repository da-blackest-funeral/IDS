<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use App\Services\Helpers\Interfaces\SalaryHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    // todo сделать второй интерфейс - installation salary helper interface, туда добавить методы removeNoInstallation
    // и т.д., а так же другой класс, я думаю с наследованием от этого
    class SalaryHelper implements SalaryHelperInterface
    {
        /**
         * @param int|float $sum
         * @param InstallerSalary|null $salary
         * @return void
         */
        public function update(int|float $sum, InstallerSalary $salary = null) {
            if (is_null($salary)) {
                $salary = $this->salary()
                    ->first();
                $salary->type = SalaryType::determine(\ProductHelper::getProduct());
            }

            $salary->sum = $sum;

            $salary->update();
        }

        /**
         * @param bool $condition
         * @param int|float $sum
         * @return void
         */
        public function updateIf(bool $condition, int|float $sum) {
            if ($condition) {
                $this->update($sum);
            }
        }

        /**
         * @param float|null $sum
         * @return InstallerSalary
         */
        public function make(float $sum = null): InstallerSalary {
            $order = \OrderHelper::getOrder();
            return InstallerSalary::create([
                'installer_id' => $order->installer_id,
                'category_id' => request()->input('categories'),
                'order_id' => $order->id,
                'sum' => $sum ?? Calculator::getInstallersWage(),
                'comment' => 'Пока не готово',
                'status' => false,
                'changed_sum' => Calculator::getInstallersWage(),
                'created_user_id' => auth()->user()->getAuthIdentifier(),
                'type' => SalaryType::determine(),
            ]);
        }

        /**
         * @return void
         */
        public function removeNoInstallation(): void {
            $this->salariesNoInstallation()
                ->each(function (InstallerSalary $salary) {
                    $this->update(0, $salary);
                });
        }

        /**
         * @return void
         */
        public function removeDelivery() {
            $this->salariesNoInstallation()
                ->each(function (InstallerSalary $salary) {
                    $salary->sum -= SystemVariables::value('delivery');
                    $salary->update();
                });
        }

        protected function salariesNoInstallation() {
            return \OrderHelper::getOrder()
                ->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get();
        }

        public function restoreNoInstallation() {
            $order = \ProductHelper::getProduct()->order;

            $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get()
                ->each(function (InstallerSalary $salary) use ($order) {
                    $this->update($this->noInstallationSalarySum($order), $salary);
                });
        }

        protected function noInstallationSalarySum(Order $order): int|float {
            $result = 0;
            // todo потом заменить на $order->measuring - флаг, выставляемый в общих настройках заказа
            if ($order->measuring_price || $order->measuring) {
                $result += SystemVariables::value('measuringWage');
            }

            if ($order->delivery) {
                $result += SystemVariables::value('delivery');
            }

            return $result;
        }

        public function salary() {
            $productInOrder = \ProductHelper::getProduct();
            $salary = $productInOrder->order
                ->salaries()
                ->where('category_id', $productInOrder->category_id);
            if (!$salary->exists()) {
                return $productInOrder->order
                    ->salaries()
                    ->first();
            }

            return $salary;
        }

        /**
         * @return bool
         */
        public function hasSalaryNoInstallation(): bool {
            return \OrderHelper::getOrder()
                ->salaries
                ->contains(function (InstallerSalary $salary) {
                    return $salary->type == SalaryTypesEnum::NO_INSTALLATION->value && $salary->sum > 0;
                });
        }

        public function checkMeasuringAndDelivery() {
            $order = \ProductHelper::getProduct()->order;
            if (\OrderHelper::hasInstallation() || Calculator::productNeedInstallation()) {
                $order->measuring_price = 0;
            } else {
                $order->measuring_price = $order->measuring ?
                    SystemVariables::value('measuring')
                    : 0;
                // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
                $this->updateIf(!$this->hasSalaryNoInstallation(), Calculator::getInstallersWage());
            }
        }
    }
