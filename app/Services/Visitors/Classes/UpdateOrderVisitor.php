<?php

    namespace App\Services\Visitors\Classes;

    use App\Models\Order;
    use App\Models\SystemVariables;
    use App\Services\Commands\Classes\RemoveAdditionalVisitsCommand;
    use App\Services\Commands\Classes\RemoveDeliveryCommand;
    use App\Services\Commands\Classes\RemoveDeliveryKilometresCommand;
    use App\Services\Commands\Classes\RestoreDeliveryCommand;
    use App\Services\Commands\Classes\SetAdditionalVisitsCommand;
    use App\Services\Commands\Classes\DeliveryKilometresCommand;

    class UpdateOrderVisitor extends AbstractVisitor
    {
        public function final() {
            return order()->update();
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
                new RestoreDeliveryCommand(order(), \OrderHelper::getProductRepository()) :
                new RemoveDeliveryCommand(order());
        }

        /**
         * @param int|null $sale
         * @return void
         */
        public function visitSale(int $sale = null) {
            order()->discount = $sale ?? (int)request()->input('sale', 0);
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
            \order()->installer_id = \request()->input('installer', firstInstaller('id'));
        }

        /**
         * @return void
         */
        protected function visitMeasuring() {
            $needMeasuring = \request()->input('measuring', false);
            $measuringPrice = systemVariable('measuring');

            $this->checkChangedMeasuring(\order(), $measuringPrice);

            \order()->measuring = $needMeasuring;
            \order()->measuring_price = (int)$needMeasuring * $measuringPrice;
        }

        /**
         * @return void
         */
        protected function visitCountAdditionalVisits() {
            $visits = (int)request()->input('count-additional-visits', 0);
            $this->commands[] = $visits ?
                new SetAdditionalVisitsCommand(\order(), $visits) :
                new RemoveAdditionalVisitsCommand(\order());
        }

        /**
         * @param Order $order
         * @param int $measuringPrice
         * @return void
         */
        protected function checkChangedMeasuring(Order $order, int $measuringPrice) {
            if ($order->measuring && !\request()->input('measuring', false)) {
                $order->price -= $measuringPrice;
            }

            if (!$order->measuring && \request()->input('measuring', false)) {
                $order->price += $measuringPrice;
            }
        }

        /**
         * @return void
         */
        protected function visitKilometres() {
            $kilometres = (int)request()->input('kilometres', 0);
            $this->commands[] = new DeliveryKilometresCommand($kilometres, \order());
        }

        protected function visitAddress() {
            // TODO: Implement visitAddress() method.
        }

        protected function visitAutoSale() {
            // TODO: Implement visitAutoSale() method.
        }

        protected function visitPrepayment() {
            order()->prepayment = request()->input('prepayment', 0);
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
            \order()->comment = request()
                ->input('all-order-comment', 'Комментарий отсутствует');
        }

        protected function visitWish() {
            // TODO: Implement visitWish() method.
        }
    }
