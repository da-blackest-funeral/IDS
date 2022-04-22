<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class OrderHelper
    {
        public function make() {
            return Order::create([
                'delivery' => Calculator::getDeliveryPrice(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                'installer_id' => request()->input('installer') ?? 2,
                'price' => Calculator::getPrice(),
                'discounted_price' => Calculator::getPrice(), // todo сделать расчет с учетом скидок
                'measuring' => Calculator::getNeedMeasuring(),
                'measuring_price' => Calculator::getMeasuringPrice(),
                'discounted_measuring_price' => Calculator::getMeasuringPrice(), // todo скидки
                'comment' => request()->input('comment') ?? 'Комментарий отсутствует',
                'products_count' => Calculator::getCount(),
                'installing_difficult' => request()->input('coefficient'),
                'is_private_person' => request()->input('person') == 'physical',
                'structure' => 'Пока не готово',
            ]);
        }

        public function addProductTo(Order $order) {
            $newProductPrice = Calculator::getPrice();

            if ($order->measuring_price) {
                $newProductPrice -= Calculator::getMeasuringPrice();
                if (Calculator::productNeedInstallation()) {
                    $order->price -= $order->measuring_price;
                    $order->measuring_price = 0;
                }
            }

            if ($order->delivery) {
                $newProductPrice -= min(
                    $order->delivery,
                    Calculator::getDeliveryPrice()
                );

                $order->delivery = max(
                    Calculator::getDeliveryPrice(),
                    $order->delivery
                );
            }

            $order->price += $newProductPrice;
            $order->products_count += Calculator::getCount();

            $order->update();

            $product = \ProductHelper::make($order->refresh());

            \ProductHelper::updateOrCreateSalary($product);

            return $product;
        }

        public function hasInstallation(Order $order): bool {
            return $order->products->contains(function ($product) {
                return \ProductHelper::hasInstallation($product);
            });
        }
    }
