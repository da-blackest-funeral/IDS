<?php

    namespace App\Services\Repositories\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    // todo сделать как real-time facade
    interface ProductRepositoryInterface
    {
        public function use(Collection $products): ProductRepositoryInterface;

        public function without(ProductInOrder $productInOrder): ProductRepositoryInterface;

        public function count(): int;

        public function byCategory(ProductInOrder $productInOrder): ProductRepositoryInterface;

        public function isNotEmpty(): bool;
    }
