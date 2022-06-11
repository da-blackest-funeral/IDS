<?php

    namespace App\Services\Repositories\Classes;

    class UpdateOrderDto
    {
        /**
         * @var bool
         */
        private bool $needDelivery;

        /**
         * @var bool
         */
        private bool $needMeasuring;

        /**
         * @var int
         */
        private int $measuringPrice;

        /**
         * @var int
         */
        private int $countAdditionalVisits;

        /**
         * @var int
         */
        private int $kilometres;

        /**
         * @return bool
         */
        public function isNeedDelivery(): bool {
            return $this->needDelivery;
        }

        /**
         * @param bool $needDelivery
         * @return UpdateOrderDto
         */
        public function setNeedDelivery(bool $needDelivery): UpdateOrderDto {
            $this->needDelivery = $needDelivery;
            return $this;
        }

        /**
         * @return bool
         */
        public function isNeedMeasuring(): bool {
            return $this->needMeasuring;
        }

        /**
         * @param bool $needMeasuring
         * @return UpdateOrderDto
         */
        public function setNeedMeasuring(bool $needMeasuring): UpdateOrderDto {
            $this->needMeasuring = $needMeasuring;
            return $this;
        }

        /**
         * @return int
         */
        public function getMeasuringPrice(): int {
            return $this->measuringPrice;
        }

        /**
         * @param int $measuringPrice
         * @return UpdateOrderDto
         */
        public function setMeasuringPrice(int $measuringPrice): UpdateOrderDto {
            $this->measuringPrice = $measuringPrice;
            return $this;
        }

        /**
         * @return int
         */
        public function getCountAdditionalVisits(): int {
            return $this->countAdditionalVisits;
        }

        /**
         * @param int $countAdditionalVisits
         * @return UpdateOrderDto
         */
        public function setCountAdditionalVisits(int $countAdditionalVisits): UpdateOrderDto {
            $this->countAdditionalVisits = $countAdditionalVisits;
            return $this;
        }

        /**
         * @return int
         */
        public function getKilometres(): int {
            return $this->kilometres;
        }

        /**
         * @param int $kilometres
         * @return UpdateOrderDto
         */
        public function setKilometres(int $kilometres): UpdateOrderDto {
            $this->kilometres = $kilometres;
            return $this;
        }

        /**
         * @param array $orderData
         */
        public function __construct(array $orderData) {
            $this->needDelivery = $orderData['delivery'] ?? false;
            $this->kilometres = $orderData['kilometres'] ?? 0;
            $this->countAdditionalVisits = $orderData['count-additional-visits'] ?? 0;
            $this->measuringPrice = $orderData['measuring-price'];
            $this->needMeasuring = $orderData['measuring'] ?? false;
        }
    }
