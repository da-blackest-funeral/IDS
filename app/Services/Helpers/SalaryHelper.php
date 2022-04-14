<?php

    namespace App\Services\Helpers;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class SalaryHelper
    {
        public static function make(Order $order) {
            return InstallerSalary::create([
                'installer_id' => $order->installer_id,
                'category_id' => request()->input('categories'),
                'order_id' => $order->id,
                'sum' => Calculator::getInstallersWage(),
                'comment' => 'Пока не готово',
                'status' => false,
                'changed_sum' => Calculator::getInstallersWage(),
                'created_user_id' => auth()->user()->getAuthIdentifier(),
                'type' => 'Заказ', // todo сделать Enum для этого
            ]);
        }

        public static function getSalary(ProductInOrder $productInOrder) {
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

        public static function updateSalary(int|float $sum, ProductInOrder $productInOrder) {
            $salary = static::getSalary($productInOrder)
                ->first();

            $salary->sum = $sum;
            $salary->update();
        }

        public static function checkSalaryForMeasuringAndDelivery(Order $order, ProductInOrder $productInOrder) {
            if (OrderHelper::hasInstallation($order) || Calculator::productNeedInstallation()) {
                $order->measuring_price = 0;
            } else {
                $order->measuring_price = SystemVariables::value('measuring');
                // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
                static::updateSalary(Calculator::getInstallersWage(), $productInOrder);
            }
        }
    }
