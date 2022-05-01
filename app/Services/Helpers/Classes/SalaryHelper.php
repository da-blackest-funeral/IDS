<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Interfaces\SalaryHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class SalaryHelper implements SalaryHelperInterface
    {
        /**
         * @param int|float $sum
         * @return void
         */
        public function update(int|float $sum) {
            $salary = $this->salary()
                ->first();

            $salary->sum = $sum;
            $salary->type = SalaryType::determine(\ProductHelper::getProduct());

            $salary->update();
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

        public function removeNoInstallation() {
            \ProductHelper::getProduct()
                ->order
                ->salaries()
                ->where('type', SalaryType::NO_INSTALLATION)
                ->get()
                ->each(function (InstallerSalary $salary) {
                    $salary->sum = 0;
                    $salary->update();
                });
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

        function checkMeasuringAndDelivery() {
            $productInOrder = \ProductHelper::getProduct();
            if (\OrderHelper::hasInstallation() || Calculator::productNeedInstallation()) {
                $productInOrder->order->measuring_price = 0;
            } else {
                $productInOrder->order->measuring_price = SystemVariables::value('measuring');
                // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
                $this->update(Calculator::getInstallersWage());
            }
        }
    }
