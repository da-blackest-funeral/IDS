<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;

    class CreateSalaryDto
    {
        /**
         * @var Order
         */
        private Order $order;

        private int $installerId;

        /**
         * @return int
         */
        public function getInstallerId(): int {
            return $this->installerId;
        }

        /**
         * @param int $installerId
         * @return CreateSalaryDto
         */
        public function setInstallerId(int $installerId): CreateSalaryDto {
            $this->installerId = $installerId;
            return $this;
        }

        /**
         * @return Order
         */
        public function getOrder(): Order {
            return $this->order;
        }

        /**
         * @param Order $order
         * @return CreateSalaryDto
         */
        public function setOrder(Order $order): CreateSalaryDto {
            $this->order = $order;
            return $this;
        }

        /**
         * @return float
         */
        public function getInstallersWage(): float {
            return $this->installersWage;
        }

        /**
         * @param float $installersWage
         * @return CreateSalaryDto
         */
        public function setInstallersWage(float $installersWage): CreateSalaryDto {
            $this->installersWage = $installersWage;
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
         * @return CreateSalaryDto
         */
        public function setComment(string $comment): CreateSalaryDto {
            $this->comment = $comment;
            return $this;
        }

        /**
         * @return bool
         */
        public function getStatus(): bool {
            return $this->status;
        }

        /**
         * @param bool $status
         * @return CreateSalaryDto
         */
        public function setStatus(bool $status): CreateSalaryDto {
            $this->status = $status;
            return $this;
        }

        /**
         * @return float
         */
        public function getChangedSum(): float {
            return $this->changedSum;
        }

        /**
         * @param float $changedSum
         * @return CreateSalaryDto
         */
        public function setChangedSum(float $changedSum): CreateSalaryDto {
            $this->changedSum = $changedSum;
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
         * @return CreateSalaryDto
         */
        public function setUserId(int $userId): CreateSalaryDto {
            $this->userId = $userId;
            return $this;
        }

        /**
         * @return string
         */
        public function getType(): string {
            return $this->type;
        }

        /**
         * @param string $type
         * @return CreateSalaryDto
         */
        public function setType(string $type): CreateSalaryDto {
            $this->type = $type;
            return $this;
        }

        /**
         * @return int
         */
        public function getCategory(): int {
            return $this->category;
        }

        /**
         * @param int $category
         * @return CreateSalaryDto
         */
        public function setCategory(int $category): CreateSalaryDto {
            $this->category = $category;
            return $this;
        }

        /**
         * @var int
         */
        private int $category;

        /**
         * @var float
         */
        private float $installersWage;

        /**
         * @var string
         */
        private string $comment;

        /**
         * @var bool
         */
        private bool $status;

        /**
         * @var float
         */
        private float $changedSum;

        /**
         * @var int
         */
        private int $userId;

        /**
         * @var string
         */
        private string $type;
    }
