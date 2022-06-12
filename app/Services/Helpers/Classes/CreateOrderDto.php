<?php

    namespace App\Services\Helpers\Classes;

    class CreateOrderDto
    {
        /**
         * @var int
         */
        private int $deliveryPrice;

        /**
         * @var float
         */
        private float $installingDifficult;

        /**
         * @return float
         */
        public function getInstallingDifficult(): float {
            return $this->installingDifficult;
        }

        /**
         * @param float $installingDifficult
         * @return CreateOrderDto
         */
        public function setInstallingDifficult(float $installingDifficult): CreateOrderDto {
            $this->installingDifficult = $installingDifficult;
            return $this;
        }

        /**
         * @var bool
         */
        private bool $needDelivery;

        /**
         * @return bool
         */
        public function isNeedDelivery(): bool {
            return $this->needDelivery;
        }

        /**
         * @param bool $needDelivery
         * @return CreateOrderDto
         */
        public function setNeedDelivery(bool $needDelivery): CreateOrderDto {
            $this->needDelivery = $needDelivery;
            return $this;
        }

        /**
         * @var int
         */
        private int $userId;

        /**
         * @var int
         */
        private int $installerId;

        /**
         * @var int
         */
        private int $price;

        /**
         * @var int
         */
        private int $discount;

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
        private int $discountedMeasuringPrice;

        /**
         * @var string
         */
        private string $comment;

        /**
         * @var int
         */
        private int $productCount;

        /**
         * @var bool
         */
        private bool $isPrivatePerson;

        /**
         * @var string
         */
        private string $structure;

        /**
         * @return int
         */
        public function getDeliveryPrice(): int {
            return $this->deliveryPrice;
        }

        /**
         * @param int $deliveryPrice
         * @return CreateOrderDto
         */
        public function setDeliveryPrice(int $deliveryPrice): CreateOrderDto {
            $this->deliveryPrice = $deliveryPrice;
            return $this;
        }

        /**
         * @return int
         */
        public function getUserId(): int {
            return $this->userId;
        }

        /**
         * @param int $userId
         * @return CreateOrderDto
         */
        public function setUserId(int $userId): CreateOrderDto {
            $this->userId = $userId;
            return $this;
        }

        /**
         * @return int
         */
        public function getInstallerId(): int {
            return $this->installerId;
        }

        /**
         * @param int $installerId
         * @return CreateOrderDto
         */
        public function setInstallerId(int $installerId): CreateOrderDto {
            $this->installerId = $installerId;
            return $this;
        }

        /**
         * @return int
         */
        public function getPrice(): int {
            return $this->price;
        }

        /**
         * @param int $price
         * @return CreateOrderDto
         */
        public function setPrice(int $price): CreateOrderDto {
            $this->price = $price;
            return $this;
        }

        /**
         * @return int
         */
        public function getDiscount(): int {
            return $this->discount;
        }

        /**
         * @param int $discount
         * @return CreateOrderDto
         */
        public function setDiscount(int $discount): CreateOrderDto {
            $this->discount = $discount;
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
         * @return CreateOrderDto
         */
        public function setNeedMeasuring(bool $needMeasuring): CreateOrderDto {
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
         * @return CreateOrderDto
         */
        public function setMeasuringPrice(int $measuringPrice): CreateOrderDto {
            $this->measuringPrice = $measuringPrice;
            return $this;
        }

        /**
         * @return int
         */
        public function getDiscountedMeasuringPrice(): int {
            return $this->discountedMeasuringPrice;
        }

        /**
         * @param int $discountedMeasuringPrice
         * @return CreateOrderDto
         */
        public function setDiscountedMeasuringPrice(int $discountedMeasuringPrice): CreateOrderDto {
            $this->discountedMeasuringPrice = $discountedMeasuringPrice;
            return $this;
        }

        /**
         * @return string
         */
        public function getComment(): string {
            return $this->comment;
        }

        /**
         * @param string $comment
         * @return CreateOrderDto
         */
        public function setComment(string $comment): CreateOrderDto {
            $this->comment = $comment;
            return $this;
        }

        /**
         * @return int
         */
        public function getProductCount(): int {
            return $this->productCount;
        }

        /**
         * @param int $productCount
         * @return CreateOrderDto
         */
        public function setProductCount(int $productCount): CreateOrderDto {
            $this->productCount = $productCount;
            return $this;
        }

        /**
         * @return bool
         */
        public function isPrivatePerson(): bool {
            return $this->isPrivatePerson;
        }

        /**
         * @param bool $isPrivatePerson
         * @return CreateOrderDto
         */
        public function setIsPrivatePerson(bool $isPrivatePerson): CreateOrderDto {
            $this->isPrivatePerson = $isPrivatePerson;
            return $this;
        }

        /**
         * @return string
         */
        public function getStructure(): string {
            return $this->structure;
        }

        /**
         * @param string $structure
         * @return CreateOrderDto
         */
        public function setStructure(string $structure): CreateOrderDto {
            $this->structure = $structure;
            return $this;
        }
    }
