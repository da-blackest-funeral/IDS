<?php

    namespace App\Services\Repositories\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    // todo сделать как real-time facade
    interface ProductRepositoryInterface
    {
        public static function use(Collection $products): ProductRepositoryInterface;

        public function without(object $productToReject): ProductRepositoryInterface;

        public function count(): int;

        public static function byCategory(ProductInOrder $productInOrder): ProductRepositoryInterface;

        public function isNotEmpty(): bool;

        public function get(): Collection;
    }
