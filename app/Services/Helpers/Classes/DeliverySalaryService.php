<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;

    class DeliverySalaryService
    {
        /**
         * @return void
         */
        public function restore(InstallerSalary $salary, int $visits) {
            $salary->sum += SystemVariables::value('delivery')
                * ($visits);

            $salary->update();
        }

        /**
         * @param InstallerSalary $salary
         * @param int $deliverySalary
         * @return void
         */
        public function remove(InstallerSalary $salary, int $deliverySalary) {
            $salary->sum -= $deliverySalary;
            $salary->update();
        }
    }
