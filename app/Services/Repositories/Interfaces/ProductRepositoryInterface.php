<?php

    namespace App\Services\Repositories\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface ProductRepositoryInterface
    {
        public static function use(Collection $products): ProductRepositoryInterface;

        public function without(object $productToReject): ProductRepositoryInterface;

        public function count(): int;

        public static function byCategory(ProductInOrder $productInOrder): ProductRepositoryInterface;

        public function hasInstallation(): bool;

        public function onlyWithInstallation(): ProductRepositoryInterface;

        public function isEmpty(): bool;

        public function isNotEmpty(): bool;

        public function get(): Collection;

        public function has(Callable $callback): bool;

        public function maxDelivery(): int|null;
    }
