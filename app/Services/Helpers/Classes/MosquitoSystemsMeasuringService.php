<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Interfaces\MeasuringService;

    class MosquitoSystemsMeasuringService implements MeasuringService
    {
        /**
         * @param Order $order
         * @param bool $productNeedInstallation
         */
        public function __construct(
            private readonly Order $order,
            private readonly bool $productNeedInstallation
        ) {}

        /**
         * @param int $measuringPrice
         * @return void
         */
        public function calculateMeasuringOptions(int $measuringPrice): void {
            $this->notNeedMeasuring() ?
                $this->removeMeasuring($measuringPrice) :
                $this->restoreMeasuring();
        }

        /**
         * @return Order
         */
        public function getResultOrder(): Order {
            return $this->order;
        }

        /**
         * @return bool
         */
        protected function notNeedMeasuring(): bool {
            return $this->order->measuring_price || \OrderService::hasInstallation();
        }

        /**
         * @return void
         */
        protected function removeMeasuring(int $measuringPrice) {
            $this->order->price -= $measuringPrice;
            if ($this->productNeedInstallation) {
                $this->deductMeasuringPrice();
            }
        }

        /**
         * @return void
         */
        protected function deductMeasuringPrice() {
            $this->order->price -= $this->order->measuring_price;
            $this->order->measuring_price = 0;
        }

        /**
         * @return void
         */
        protected function restoreMeasuring() {
            if (!$this->productNeedInstallation || deletingProduct()) {
                $this->order->measuring_price = SystemVariables::value('measuring');
            }

            if (deletingProduct()) {
                $this->order->price += $this->order->measuring_price;
            }
        }
    }
