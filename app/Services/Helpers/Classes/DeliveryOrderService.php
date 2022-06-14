<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Services\Helpers\Interfaces\DeliveryService;
    use App\Services\Calculator\Interfaces\Calculator;

    class DeliveryOrderService implements DeliveryService
    {
        /**
         * @param Order $order
         * @param int $maxDeliveryPrice
         * @param Calculator $calculator
         */
        public function __construct(
            private readonly Order $order,
            private readonly int $maxDeliveryPrice,
            private readonly Calculator $calculator
        ) {}

        /**
         * @return void
         */
        public function calculateDeliveryOptions() {
            if ($this->order->need_delivery) {
                $this->decreasePriceByDelivery();
                $this->determineMaxDelivery();
            }
        }

        /**
         * @return Order
         */
        public function getResultOrder(): Order {
            return $this->order;
        }

        /**
         * @return void
         */
        protected function decreasePriceByDelivery() {
            if (!deletingProduct()) {
                $this->order->price -= min(
                    $this->order->delivery,
                    $this->calculator->getDeliveryPrice()
                );
            } else {
                $this->deliveryWhenDeletingProduct();
            }
        }

        /**
         * @return void
         */
        protected function deliveryWhenDeletingProduct() {
            $this->order->price -= max(
                $this->order->delivery,
                $this->maxDeliveryPrice
            );

            $this->order->price += $this->maxDeliveryPrice;
        }

        /**
         * @return void
         */
        protected function determineMaxDelivery() {
            $this->order->delivery = $this->order->need_delivery ? max(
                $this->calculator->getDeliveryPrice(),
                $this->maxDeliveryPrice
            ) : 0;
        }
    }
