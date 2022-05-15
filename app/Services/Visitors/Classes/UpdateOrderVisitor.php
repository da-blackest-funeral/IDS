<?php

    namespace App\Services\Visitors\Classes;

    use App\Models\Order;
    use App\Models\SystemVariables;

    class UpdateOrderVisitor extends AbstractVisitor
    {
        protected function final() {
            \order()->update();
        }

        public function convertToMethod(string $name): string {
            return 'visit' . \Str::of($name)->camel()->ucfirst()->__toString();
        }

        protected function visitDelivery() {
            if (!request()->input('delivery', false)) {
                if (\order()->need_delivery) {
                    \SalaryHelper::removeDelivery();
                    \order()->price -= \order()->delivery;
                    \order()->delivery = 0;
                }

                \order()->need_delivery = false;
            } else {
                $delivery = \OrderHelper::getProductRepository()
                    ->maxDelivery();

                if (!\order()->need_delivery) {
                    \SalaryHelper::restoreDelivery();
                    \order()->price += $delivery;
                    \order()->delivery = $delivery;
                }

                \order()->need_delivery = true;
            }
        }

        protected function visitSale() {
            $sale = (int)\request()->input('sale', 0);
            \order()->discounted_price = $sale ?
                \order()->price * (1 - $sale / 100) :
                \order()->discounted_price = \order()->price;
        }

        protected function visitAdditionalSale() {
            // TODO: Implement visitAdditionalSale() method.
        }

        protected function visitInstaller() {
            \order()->installer_id = request()->input('installer', firstInstaller('id'));
        }

        protected function visitMinimalOrderSum() {
            // TODO: Implement visitMinimalOrderSum() method.
        }

        protected function visitMeasuring() {
            $needMeasuring = \request()->input('measuring', false);
            $measuringPrice = SystemVariables::value('measuring');

            $this->checkChangedMeasuring(\order(), $measuringPrice);

            \order()->measuring = $needMeasuring;
            \order()->measuring_price = (int)$needMeasuring * $measuringPrice;
        }

        protected function visitCountAdditionalVisits() {
            // TODO: Implement visitCountAdditionalVisits() method.
        }

        protected function checkChangedMeasuring(Order $order, int $measuringPrice) {
            if ($order->measuring && !\request()->input('measuring', false)) {
                $order->price -= $measuringPrice;
            }

            if (!$order->measuring && \request()->input('measuring', false)) {
                $order->price += $measuringPrice;
            }
        }

        protected function visitKilometres() {
            // TODO: Implement visitKilometres() method.
        }

        protected function visitAddress() {
            // TODO: Implement visitAddress() method.
        }

        protected function visitAutoSale() {
            // TODO: Implement visitAutoSale() method.
        }

        protected function visitPrepayment() {
            \order()->prepayment = \request()->input('prepayment', 0);
        }

        protected function visitPerson() {
            // TODO: Implement visitPerson() method.
        }

        protected function visitMinimalSum() {
            // TODO: Implement visitMinimalSum() method.
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
