<?php

    namespace App\Services\Visitors\Classes;

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
                'productInOrder'
            ]);
        }

        public function execute() {
            foreach ($this->visitItems as $visitItem => $value) {
                $method = "visit{$this->convertToMethod($visitItem)}";
                $this->$method();
            }
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
            if (! \request()->input('delivery')) {
                if ($order->need_delivery) {
                    \SalaryHelper::removeDelivery();
                    $order->price -= $order->delivery;
                    $order->delivery = 0;
                }

                $order->need_delivery = false;
                $order->update();
            } else {
                $order->update([
                    'need_delivery' => true
                ]);
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
            // TODO: Implement visitMeasuring() method.
        }

        public function visitCountAdditionalVisits() {
            // TODO: Implement visitCountAdditionalVisits() method.
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
            // TODO: Implement visitAllOrderComment() method.
        }

        public function visitWish() {
            // TODO: Implement visitWish() method.
        }
    }
