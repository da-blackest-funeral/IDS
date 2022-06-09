<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Salaries\InstallerSalary;
    use App\Services\Helpers\Classes\CreateSalaryDto;

    // todo добавить остальные методы сюда
    interface SalaryHelperInterface
    {
        /**
         * @param int|float $sum
         * @param InstallerSalary|null $salary
         * @return mixed
         */
        public function update(int|float $sum, InstallerSalary $salary = null);

        /**
         * @param float|null $sum
         * @return mixed
         */
        public function make(CreateSalaryDto $dto = null, float $sum = null);

        /**
         * @return mixed
         */
        public function salary();

        /**
         * @return mixed
         */
        function checkMeasuringAndDelivery();
    }
