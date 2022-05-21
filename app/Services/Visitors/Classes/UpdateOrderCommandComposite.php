<?php

    namespace App\Services\Visitors\Classes;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Commands\Classes\RemoveAdditionalVisitsCommand;
    use App\Services\Commands\Classes\RemoveDeliveryCommand;
    use App\Services\Commands\Classes\RemoveMeasuringCommand;
    use App\Services\Commands\Classes\RestoreDeliveryCommand;
    use App\Services\Commands\Classes\RestoreMeasuringCommand;
    use App\Services\Commands\Classes\SetAdditionalVisitsCommand;
    use App\Services\Commands\Classes\DeliveryKilometresCommand;
    use App\Services\Commands\Interfaces\Command;

    class UpdateOrderCommandComposite extends AbstractCommandComposite
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
        ) {
        }

        public function result() {
            return $this->order->update() && $this->salary->update();
        }

        /**
         * @return UpdateOrderCommandComposite
         */
        protected function deliveryCommand() {
            /** @var Command $command */
            $command = $this->commandData->isNeedDelivery() ?
                new RestoreDeliveryCommand($this->order, \OrderHelper::getProductRepository()) :
                new RemoveDeliveryCommand($this->order);

            $this->addCommand($command);

            return $this;
        }

//        /**
//         * @param int|null $sale
//         * @return void
//         */
//        public function visitSale(int $sale = null) {
//            $this->order->discount = $sale ?? (int)request()->input('sale', 0);
//        }

//        /**
//         * @return void
//         */
//        protected function visitInstaller() {
//            $this->order->installer_id = \request()->input('installer', firstInstaller('id'));
//        }

        /**
         * @return UpdateOrderCommandComposite
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
         * @return UpdateOrderCommandComposite
         */
        protected function countAdditionalVisitsCommand() {
            $visits = $this->commandData->getCountAdditionalVisits();
            /** @var Command $command */
            $command = $visits > 0 ?
                new SetAdditionalVisitsCommand($this->order, $visits) :
                new RemoveAdditionalVisitsCommand($this->order, $this->salary);

            $this->addCommand($command);

            return $this;
        }

        /**
         * @return UpdateOrderCommandComposite
         */
        protected function kilometresCommand() {
            $this->addCommand(new DeliveryKilometresCommand(
                $this->commandData->getKilometres(),
                $this->order
            ));

            return $this;
        }

        public function commands(): UpdateOrderCommandComposite {
            $this->countAdditionalVisitsCommand();
            $this->deliveryCommand();
            $this->measuringCommand();
            $this->kilometresCommand();

            return $this;
        }

//        protected function visitPrepayment() {
//            $this->order->prepayment = request()->input('prepayment', 0);
//        }

//        protected function visitMinimalSum() {
//            SystemVariables::updateByName('minSumOrder', request()->input('minimal-sum'));
//        }

//        protected function visitAllOrderComment() {
//            $this->order->comment = request()
//                ->input('all-order-comment', 'Комментарий отсутствует');
//        }
    }
