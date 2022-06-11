<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Salaries\InstallerSalary;

    // todo добавить остальные методы сюда
    interface SalaryServiceInterface
    {
        /**
         * @param int|float $sum
         * @param InstallerSalary|null $salary
         * @return mixed
         */
        public function update(int|float $sum, InstallerSalary $salary = null);

        /**
         * @return mixed
         */
        public function salary();

        /**
         * @return mixed
         */
        function checkMeasuringAndDelivery();
    }
