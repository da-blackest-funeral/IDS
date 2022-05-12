<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;

    // todo добавить остальные методы сюда
    interface SalaryHelperInterface
    {
        /**
         * @param int|float $sum
         * @return mixed
         */
        public function update(int|float $sum, InstallerSalary $salary = null);

        /**
         * @param float|null $sum
         * @return mixed
         */
        public function make(float $sum = null);

        /**
         * @return mixed
         */
        public function salary();

        /**
         * @return mixed
         */
        function checkMeasuringAndDelivery();
    }
