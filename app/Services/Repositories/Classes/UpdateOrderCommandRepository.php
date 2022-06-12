<?php

    namespace App\Services\Repositories\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Classes\DeliveryKilometresCommand;
    use App\Services\Commands\Classes\RemoveAdditionalVisitsCommand;
    use App\Services\Commands\Classes\RemoveDeliveryCommand;
    use App\Services\Commands\Classes\RemoveMeasuringCommand;
    use App\Services\Commands\Classes\RestoreDeliveryCommand;
    use App\Services\Commands\Classes\RestoreMeasuringCommand;
    use App\Services\Commands\Classes\SetAdditionalVisitsCommand;
    use App\Services\Commands\Interfaces\Command;

    class UpdateOrderCommandRepository extends AbstractCommandRepository
    {
        /**
         * @param UpdateOrderDto $commandData
         * @param Order $order
         * @param InstallerSalary $salary
         */
        public function __construct(
            private readonly UpdateOrderDto $commandData,
            private readonly Order $order,
            private readonly InstallerSalary $salary
        ) {}

        public function result() {
            return $this->order->update() && $this->salary->update();
        }

        /**
         * @return UpdateOrderCommandRepository
         */
        protected function deliveryCommand() {
            /** @var Command $command */
            $command = $this->commandData->isNeedDelivery() ?
                new RestoreDeliveryCommand($this->order, \OrderService::getProductRepository()) :
                new RemoveDeliveryCommand($this->order, $this->salary);

            $this->addCommand($command);

            return $this;
        }

        /**
         * @return UpdateOrderCommandRepository
         */
        protected function measuringCommand() {
            /** @var Command $command */
            $command = $this->commandData->isNeedMeasuring() ?
                new RestoreMeasuringCommand(
                    order: $this->order,
                    salary: $this->salary
                ) :
                new RemoveMeasuringCommand(
                    order: $this->order,
                    measuringPrice: $this->commandData->getMeasuringPrice(),
                    salary: $this->salary
                );
            $this->addCommand($command);

            return $this;
        }

        /**
         * @return UpdateOrderCommandRepository
         */
        protected function countAdditionalVisitsCommand() {
            $visits = $this->commandData->getCountAdditionalVisits();
            /** @var Command $command */
            $command = $visits > 0 ?
                new SetAdditionalVisitsCommand(
                    order: $this->order,
                    salary: $this->salary,
                    visits: $visits
                ) :
                new RemoveAdditionalVisitsCommand($this->order, $this->salary);

            $this->addCommand($command);

            return $this;
        }

        /**
         * @return UpdateOrderCommandRepository
         */
        protected function kilometresCommand() {
            $this->addCommand(new DeliveryKilometresCommand(
                $this->commandData->getKilometres(),
                $this->order,
                $this->salary
            ));

            return $this;
        }

        /**
         * @return $this
         */
        public function commands(): UpdateOrderCommandRepository {
            $this->countAdditionalVisitsCommand();
            $this->deliveryCommand();
            $this->measuringCommand();
            $this->kilometresCommand();

            return $this;
        }
    }
