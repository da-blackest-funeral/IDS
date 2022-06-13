<?php

    namespace App\Services\Renderers\Interfaces;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;

    interface SelectDataInterface
    {
        public function __construct(ProductInOrder $productInOrder);

        public function secondSelect(): Collection;

        public function thirdSelect(): Collection;

        public function additional(): array;

        public function use(ProductInOrder $productInOrder): SelectDataInterface;
    }
