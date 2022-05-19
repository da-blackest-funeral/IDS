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

    class UpdateOrderVisitor extends AbstractVisitor
    {
        /**
         * @param array $visitItems
         * @param Order $order
         * @param InstallerSalary $salary
         */
        public function __construct(
            protected array $visitItems,
            private readonly Order $order,
            private readonly InstallerSalary $salary,
        ) {
            parent::__construct($visitItems);
        }

        public function final() {
            return $this->order->update() && $this->salary->update();
        }

        /**
         * @param string $name
         * @return string
         */
        protected function convertToMethod(string $name): string {
            return 'visit' . \Str::of($name)->camel()->ucfirst()->__toString();
        }

        /**
         * @return void
         */
        protected function visitDelivery() {
            $this->commands[] = request()->input('delivery', false) ?
                new RestoreDeliveryCommand($this->order, \OrderHelper::getProductRepository()) :
                new RemoveDeliveryCommand($this->order);
        }

        /**
         * @param int|null $sale
         * @return void
         */
        public function visitSale(int $sale = null) {
            $this->order->discount = $sale ?? (int)request()->input('sale', 0);
        }

        /**
         * @return void
         */
        protected function visitAdditionalSale() {
            // TODO: Implement visitAdditionalSale() method.
        }

        /**
         * @return void
         */
        protected function visitInstaller() {
            $this->order->installer_id = \request()->input('installer', firstInstaller('id'));
        }

        /**
         * @return void
         */
        protected function visitMeasuring() {
            $needMeasuring = \request()->input('measuring', false);
            $measuringPrice = (int) systemVariable('measuring');

            $this->commands[] = $needMeasuring ?
                new RestoreMeasuringCommand(
                    order: $this->order,
                    salary: $this->salary
                ) :
                new RemoveMeasuringCommand(
                    order: $this->order,
                    measuringPrice: $measuringPrice,
                    salary: $this->salary
                );
        }

        /**
         * @return void
         */
        protected function visitCountAdditionalVisits() {
            $visits = (int)request()->input('count-additional-visits', 0);
            $this->commands[] = $visits ?
                new SetAdditionalVisitsCommand($this->order, $visits) :
                new RemoveAdditionalVisitsCommand($this->order, $this->salary);
        }

        /**
         * @return void
         */
        protected function visitKilometres() {
            $kilometres = (int)request()->input('kilometres', 0);
            $this->commands[] = new DeliveryKilometresCommand($kilometres, $this->order);
        }

        protected function visitAddress() {
            // TODO: Implement visitAddress() method.
        }

        protected function visitAutoSale() {
            // TODO: Implement visitAutoSale() method.
        }

        protected function visitPrepayment() {
            $this->order->prepayment = request()->input('prepayment', 0);
        }

        protected function visitPerson() {
            // TODO: Implement visitPerson() method.
        }

        protected function visitMinimalSum() {
            SystemVariables::updateByName('minSumOrder', request()->input('minimal-sum'));
        }

        protected function visitSumManually() {
            // TODO: Implement visitSumManually() method.
        }

        protected function visitWageManually() {
            // TODO: Implement visitWageManually() method.
        }

        protected function visitAllOrderComment() {
            $this->order->comment = request()
                ->input('all-order-comment', 'Комментарий отсутствует');
        }

        protected function visitWish() {
            // TODO: Implement visitWish() method.
        }
    }
