<?php

    namespace App\Services\Repositories\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface ProductRepository
    {
        /**
         * @param Collection $products
         * @return ProductRepository
         */
        public static function use(Collection $products): ProductRepository;

        /**
         * @param object $productToReject
         * @return ProductRepository
         */
        public function remove(object $productToReject): ProductRepository;

        /**
         * @param object $productToReject
         * @return ProductRepository
         */
        public function without(object $productToReject): ProductRepository;

        /**
         * @return int
         */
        public function count(): int;

        /**
         * @param ProductInOrder $productInOrder
         * @return ProductRepository
         */
        public static function byCategory(ProductInOrder $productInOrder): ProductRepository;

        /**
         * @return bool
         */
        public function hasInstallation(): bool;

        /**
         * @return ProductRepository
         */
        public function onlyWithInstallation(): ProductRepository;

        /**
         * @return bool
         */
        public function isEmpty(): bool;

        /**
         * @return bool
         */
        public function isNotEmpty(): bool;

        /**
         * @return Collection
         */
        public function get(): Collection;

        /**
         * @param callable $callback
         * @return bool
         */
        public function has(Callable $callback): bool;

        /**
         * @return int
         */
        public function maxDelivery(): int;
    }
