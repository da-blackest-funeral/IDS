<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Helpers\Config\SalaryType;

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

        /**
         * @param Order $order
         * @param float $sum
         * @return InstallerSalary
         */
        public function create(Order $order, float $sum) {

            $dto = new CreateSalaryDto();
            $dto->setCategory(request()->input('categories', 5));
            $dto->setComment('Пока не готово');
            $dto->setInstallersWage($sum);
            $dto->setStatus(false);
            $dto->setChangedSum($dto->getInstallersWage());
            $dto->setOrder($order);
            $dto->setType(SalaryType::determine());
            $dto->setUserId(auth()->user()->getAuthIdentifier());

            return $this->make($dto);
        }
    }
