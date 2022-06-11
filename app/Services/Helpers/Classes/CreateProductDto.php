<?php

    namespace App\Services\Helpers\Classes;

    use Illuminate\Support\Collection;

    class CreateProductDto
    {
        /**
         * @var int
         */
        private int $orderId;

        /**
         * @var int
         */
        private int $installationId;

        /**
         * @var string
         */
        private string $name;

        /**
         * @var Collection
         */
        private Collection $data;

        /**
         * @var int
         */
        private int $userId;

        /**
         * @var int
         */
        private int $categoryId;

        /**
         * @var int
         */
        private int $count;

        /**
         * @var string
         */
        private string $comment;

        /**
         * @return int
         */
        public function getOrderId(): int {
            return $this->orderId;
        }

        /**
         * @param int $orderId
         * @return CreateProductDto
         */
        public function setOrderId(int $orderId): CreateProductDto {
            $this->orderId = $orderId;
            return $this;
        }

        /**
         * @return int
         */
        public function getInstallationId(): int {
            return $this->installationId;
        }

        /**
         * @param int $installationId
         * @return CreateProductDto
         */
        public function setInstallationId(int $installationId): CreateProductDto {
            $this->installationId = $installationId;
            return $this;
        }

        /**
         * @return string
         */
        public function getName(): string {
            return $this->name;
        }

        /**
         * @param string $name
         * @return CreateProductDto
         */
        public function setName(string $name): CreateProductDto {
            $this->name = $name;
            return $this;
        }

        /**
         * @return Collection
         */
        public function getData(): Collection {
            return $this->data;
        }

        /**
         * @param Collection $data
         * @return CreateProductDto
         */
        public function setData(Collection $data): CreateProductDto {
            $this->data = $data;
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
         * @return CreateProductDto
         */
        public function setUserId(int $userId): CreateProductDto {
            $this->userId = $userId;
            return $this;
        }

        /**
         * @return int
         */
        public function getCategoryId(): int {
            return $this->categoryId;
        }

        /**
         * @param int $categoryId
         * @return CreateProductDto
         */
        public function setCategoryId(int $categoryId): CreateProductDto {
            $this->categoryId = $categoryId;
            return $this;
        }

        /**
         * @return int
         */
        public function getCount(): int {
            return $this->count;
        }

        /**
         * @param int $count
         * @return CreateProductDto
         */
        public function setCount(int $count): CreateProductDto {
            $this->count = $count;
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
         * @return CreateProductDto
         */
        public function setComment(string $comment): CreateProductDto {
            $this->comment = $comment;
            return $this;
        }
    }
