<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;

    class CreateOrderService
    {
        public function make(CreateOrderDto $dto): Order {
            $order = new Order();

            $order->user_id = $dto->getUserId();
            $order->installer_id = $dto->getInstallerId();
            $order->price = $dto->getPrice();
            $order->discount = $dto->getDiscount();
            $order->measuring = $dto->isNeedMeasuring();
            $order->measuring_price = $dto->getMeasuringPrice();
            $order->discounted_measuring_price = $dto->getDiscountedMeasuringPrice();
            $order->comment = $dto->getComment();
            $order->products_count = $dto->getProductCount();
            $order->installing_difficult = $dto->getInstallingDifficult();
            $order->is_private_person = $dto->isPrivatePerson();
            $order->structure = $dto->getStructure();
            $order->delivery = $dto->getDeliveryPrice();

            $order->save();

            return $order;
        }
    }
