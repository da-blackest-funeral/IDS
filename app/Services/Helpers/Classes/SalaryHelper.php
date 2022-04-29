<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Interfaces\SalaryHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class SalaryHelper implements SalaryHelperInterface
    {
        public function update(int|float $sum, ProductInOrder $productInOrder) {
            $salary = $this->salary($productInOrder)
                ->first();

            $salary->sum = $sum;

//            if (\ProductHelper::hasInstallation($productInOrder)) {
//                $salary->type = SalaryType::INSTALLATION;
//            } else {
//                $salary->type = SalaryType::NO_INSTALLATION;
//            }
            \ProductHelper::hasInstallation($productInOrder) ?
                $salary->type = SalaryType::INSTALLATION :
                $salary->type = SalaryType::NO_INSTALLATION;

            $salary->update();
        }

        public function make(Order $order, $sum = null) {
            return InstallerSalary::create([
                'installer_id' => $order->installer_id,
                'category_id' => request()->input('categories'),
                'order_id' => $order->id,
                'sum' => $sum ?? Calculator::getInstallersWage(),
                'comment' => 'Пока не готово',
                'status' => false,
                'changed_sum' => Calculator::getInstallersWage(),
                'created_user_id' => auth()->user()->getAuthIdentifier(),
                'type' => Calculator::productNeedInstallation() ?
                    SalaryType::INSTALLATION : SalaryType::NO_INSTALLATION, // todo сделать Enum для этого
            ]);
        }

        public function salary(ProductInOrder $productInOrder) {
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

        function checkMeasuringAndDelivery(Order $order, ProductInOrder $productInOrder) {
            if (\OrderHelper::hasInstallation($order) || Calculator::productNeedInstallation()) {
                $order->measuring_price = 0;
            } else {
                $order->measuring_price = SystemVariables::value('measuring');
                // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
                $this->update(Calculator::getInstallersWage(), $productInOrder);
            }
        }
    }
