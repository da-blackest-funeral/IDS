<?php

    namespace App\Services\Visitors\Classes;

    use App\Models\Order;
    use App\Models\SystemVariables;
    use App\Services\Commands\Classes\RemoveDeliveryCommand;
    use App\Services\Commands\Classes\RestoreDeliveryCommand;
    use App\Services\Commands\Interfaces\Command;

    class UpdateOrderVisitor extends AbstractVisitor
    {
        protected function final() {
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
        private function visitDelivery() {
            /** @var Command */
            $command = request()->input('delivery', false) ?
                new RestoreDeliveryCommand() :
                new RemoveDeliveryCommand();

            $command->execute();
        }

        /**
         * @param int|null $sale
         * @return void
         */
        public function visitSale(int $sale = null) {
            order()->discount = $sale ?? (int) request()->input('sale', 0);
        }

        /**
         * @return void
         */
        private function visitAdditionalSale() {
            // TODO: Implement visitAdditionalSale() method.
        }

        /**
         * @return void
         */
        private function visitInstaller() {
            \order()->installer_id = \request()->input('installer', firstInstaller('id'));
        }

        /**
         * @return void
         */
        private function visitMeasuring() {
            $needMeasuring = \request()->input('measuring', false);
            $measuringPrice = SystemVariables::value('measuring');

            $this->checkChangedMeasuring(\order(), $measuringPrice);

            \order()->measuring = $needMeasuring;
            \order()->measuring_price = (int)$needMeasuring * $measuringPrice;
        }

        /**
         * @return void
         */
        private function visitCountAdditionalVisits() {
            // TODO: Implement visitCountAdditionalVisits() method.
        }

        /**
         * @param Order $order
         * @param int $measuringPrice
         * @return void
         */
        private function checkChangedMeasuring(Order $order, int $measuringPrice) {
            if ($order->measuring && !\request()->input('measuring', false)) {
                $order->price -= $measuringPrice;
            }

            if (!$order->measuring && \request()->input('measuring', false)) {
                $order->price += $measuringPrice;
            }
        }

        private function visitKilometres() {
            // TODO: Implement visitKilometres() method.
        }

        private function visitAddress() {
            // TODO: Implement visitAddress() method.
        }

        private function visitAutoSale() {
            // TODO: Implement visitAutoSale() method.
        }

        private function visitPrepayment() {
            \order()->prepayment = \request()->input('prepayment', 0);
        }

        private function visitPerson() {
            // TODO: Implement visitPerson() method.
        }

        private function visitMinimalSum() {
            SystemVariables::updateByName('minSumOrder', request()->input('minimal-sum'));
        }

        private function visitSumManually() {
            // TODO: Implement visitSumManually() method.
        }

        private function visitWageManually() {
            // TODO: Implement visitWageManually() method.
        }

        private function visitAllOrderComment() {
            \order()->comment = request()
                ->input('all-order-comment', 'Комментарий отсутствует');
        }

        private function visitWish() {
            // TODO: Implement visitWish() method.
        }
    }
