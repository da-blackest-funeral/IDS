<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Salaries\InstallerSalary;

    class CreateSalaryService
    {
        /**
         * @param CreateSalaryDto $dto
         * @return InstallerSalary
         */
        public function make(CreateSalaryDto $dto): InstallerSalary {
            $salary = new InstallerSalary();

            $salary->installer_id = $dto->getOrder()->installer_id;
            $salary->category_id = $dto->getCategory();
            $salary->order_id = $dto->getOrder()->id;
            $salary->sum = $dto->getInstallersWage();
            $salary->comment = $dto->getComment();
            $salary->status = $dto->getStatus();
            $salary->changed_sum = $dto->getChangedSum();
            $salary->created_user_id = $dto->getUserId();
            $salary->type = $dto->getType();

            $salary->save();
            return $salary;
        }
    }
