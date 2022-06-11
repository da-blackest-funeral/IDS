<?php

    namespace App\Services\Commands\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Interfaces\Command;

    abstract class DeliveryCommand implements Command
    {
        /**
         * @var int
         */
        protected int $deliveryPrice;

        /**
         * @var int
         */
        protected int $deliveryWage;

        public function __construct(
            protected int $kilometres,
            protected Order $order,
            protected InstallerSalary $salary
        ) {
            $this->deliveryPrice = systemVariable('additionalPriceDeliveryPerKm');
            $this->deliveryWage = systemVariable('additionalWagePerKm');
        }
    }
