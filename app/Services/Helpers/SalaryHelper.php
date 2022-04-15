<?php

    namespace App\Services\Helpers;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class SalaryHelper
    {
        /**
         * Makes new salary for given order
         *
         * @param Order $order
         * @return InstallerSalary
         */
        public static function make(Order $order): InstallerSalary{
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

        /**
         * Getting salary query builder object to given order
         *
         * @param ProductInOrder $productInOrder
         * @return object|null
         */
        public static function getSalary(ProductInOrder $productInOrder): object|null {
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
         * Updates salary for specified order
         *
         * @param int|float $sum
         * @param ProductInOrder $productInOrder
         * @return InstallerSalary
         */
        public static function updateSalary(int|float $sum, ProductInOrder $productInOrder): InstallerSalary {
            $salary = static::getSalary($productInOrder)
                ->first();

            $salary->sum = $sum;
            $salary->update();

            return $salary;
        }

        /**
         * Additional logic to calculating salary for delivery and measuring
         *
         * @todo плохо что тут помимо зарплаты ставится еще и order->measuring_price
         * @param Order $order
         * @param ProductInOrder $productInOrder
         * @return void
         */
        public static function checkSalaryForMeasuringAndDelivery(
            Order $order,
            ProductInOrder $productInOrder
        ) {
            if (OrderHelper::hasInstallation($order) || Calculator::productNeedInstallation()) {
                $order->measuring_price = 0;
            } else {
                $order->measuring_price = SystemVariables::value('measuring');
                // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
                static::updateSalary(Calculator::getInstallersWage(), $productInOrder);
            }
        }
    }
