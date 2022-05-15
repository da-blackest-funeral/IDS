<?php

    namespace App\Services\Visitors\Classes;

    use App\Models\Order;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Classes\OrderHelper;
    use App\Services\Visitors\Interfaces\Visitable;
    use App\Services\Visitors\Interfaces\Visitor;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;

    class UpdateOrderVisitor implements Visitor
    {
        protected array $visitItems;

        public function __construct(Request $request) {
            $this->visitItems = $request->except([
                '_method',
                '_token',
                'add',
                'order',
                'productInOrder',
            ]);
        }

        public function execute() {
            foreach ($this->visitItems as $visitItem => $value) {
                $method = "visit{$this->convertToMethod($visitItem)}";
                $this->$method();
            }

            \OrderHelper::getOrder()->update();
        }

        public function convertToMethod(string $name) {
            return \Str::of($name)->camel()->ucfirst()->__toString();
        }

        public function addVisitable(Visitable $visitable): Visitor {
            // TODO: Implement addVisitable() method.
        }

        public function setVisitable(Collection $visitableCollection): Visitor {
            // TODO: Implement setVisitable() method.
        }

        public function visitDelivery() {
            $order = \OrderHelper::getOrder();
            if (!request()->input('delivery', false)) {
                if ($order->need_delivery) {
                    \SalaryHelper::removeDelivery();
                    $order->price -= $order->delivery;
                    $order->delivery = 0;
                }

                $order->need_delivery = false;
            } else {
                $delivery = \OrderHelper::getProductRepository()
                    ->maxDelivery();

                if (!$order->need_delivery) {
                    \SalaryHelper::restoreDelivery();
                    $order->price += $delivery;
                    $order->delivery = $delivery;
                }

                $order->need_delivery = true;
            }
        }

        public function visitSale() {
            // TODO: Implement visitSale() method.
        }

        public function visitAdditionalSale() {
            // TODO: Implement visitAdditionalSale() method.
        }

        public function visitInstaller() {
            // TODO: Implement visitInstaller() method.
        }

        public function visitMinimalOrderSum() {
            // TODO: Implement visitMinimalOrderSum() method.
        }

        public function visitMeasuring() {
            $order = \OrderHelper::getOrder();
            $needMeasuring = \request()->input('measuring', false);
            $measuringPrice = SystemVariables::value('measuring');

            $this->checkChangedMeasuring($order, $measuringPrice);

            $order->measuring = $needMeasuring;
            $order->measuring_price = (int)$needMeasuring * $measuringPrice;
        }

        public function visitCountAdditionalVisits() {
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

        public function visitKilometres() {
            // TODO: Implement visitKilometres() method.
        }

        public function visitAddress() {
            // TODO: Implement visitAddress() method.
        }

        public function visitAutoSale() {
            // TODO: Implement visitAutoSale() method.
        }

        public function visitPrepayment() {
            // TODO: Implement visitPrepayment() method.
        }

        public function visitPerson() {
            // TODO: Implement visitPerson() method.
        }

        public function visitMinimalSum() {
            // TODO: Implement visitMinimalSum() method.
        }

        public function visitSumManually() {
            // TODO: Implement visitSumManually() method.
        }

        public function visitWageManually() {
            // TODO: Implement visitWageManually() method.
        }

        public function visitAllOrderComment() {
            \OrderHelper::getOrder()->comment = request()
                ->input('all-order-comment', 'Комментарий отсутствует');
        }

        public function visitWish() {
            // TODO: Implement visitWish() method.
        }
    }
