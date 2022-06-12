<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\ProductInOrder;

    class CreateProductService
    {
        public function make(CreateProductDto $dto): ProductInOrder {
            $product = new ProductInOrder();

            $product->order_id = $dto->getOrderId();
            $product->category_id = $dto->getCategoryId();
            $product->name = $dto->getName();
            $product->data = $dto->getData();
            $product->user_id = $dto->getUserId();
            $product->count = $dto->getCount();
            $product->installation_id = $dto->getInstallationId();
            $product->comment = $dto->getComment();

            $product->save();
            return $product;
        }
    }
