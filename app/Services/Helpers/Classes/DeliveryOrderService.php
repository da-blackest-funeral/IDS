<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Services\Helpers\Interfaces\DeliveryService;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class DeliveryOrderService implements DeliveryService
    {
        /**
         * @param Order $order
         * @param int $maxDeliveryPrice
         */
        public function __construct(
            private readonly Order $order,
            private readonly int $maxDeliveryPrice
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
         * @todo передавать сюда deliveryPrice а не вызывать фасад Calculator
         */
        protected function decreasePriceByDelivery() {
            if (!deletingProduct()) {
                $this->order->price -= min(
                    $this->order->delivery,
                    Calculator::getDeliveryPrice()
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
                Calculator::getDeliveryPrice(),
                $this->maxDeliveryPrice
            ) : 0;
        }
    }
