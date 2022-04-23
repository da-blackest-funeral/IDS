<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

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
            $order->price += Calculator::getPrice();

            if ($order->measuring_price || $this->hasInstallation($order)) {
                $order->price -= Calculator::getMeasuringPrice();
                if (Calculator::productNeedInstallation()) {
                    $order->price -= $order->measuring_price;
                    $order->measuring_price = 0;
                }
            }

//            if (!($order->measuring_price) && !Calculator::productNeedInstallation()) {
//                $order->price += $order->measuring_price;
//            }

//            if (!Calculator::productNeedInstallation() && oldProductHasInstallation()) {
//                $order->price += Calculator::getMeasuringPrice();
//            }

            if ($order->delivery) {
                $order->price -= min(
                    $order->delivery,
                    Calculator::getDeliveryPrice()
                );

                $order->delivery = max(
                    Calculator::getDeliveryPrice(),
                    $order->delivery
                );
            }

            $order->products_count += Calculator::getCount();

            $order->update();

            $product = \ProductHelper::make($order->refresh());

            \ProductHelper::updateOrCreateSalary($product);

            return $product;
        }

        public function hasInstallation(Order $order): bool {
            return $this->withoutOldProduct($order->products)->contains(function ($product) {
                return \ProductHelper::hasInstallation($product);
            }) && $this->hasProducts($order);
        }

        public function hasProducts(Order $order): bool {
            return $this->withoutOldProduct($order->products)->isNotEmpty();
        }

        public function withoutOldProduct(Collection $products): Collection {
            return $products->reject(function ($product) {
                return $product->id == oldProduct('id');
            });
        }
    }
