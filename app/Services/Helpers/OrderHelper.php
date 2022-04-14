<?php

    namespace App\Services\Helpers;

    use App\Models\Order;
    // this feature is called real-time facades
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    /*
     * In this base class there are global methods,
     * that are applies to all project
     */
    class OrderHelper
    {
        /**
         * Makes new order from request inputs and calculated prices
         *
         * @return Order
         */
        public static function make(): Order {
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

        /**
         * Creates new product and adds it to the order
         *
         * @param Order $order
         * @return ProductInOrder
         */
        public static function addProduct(Order $order): ProductInOrder {
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

            $product = ProductHelper::make($order->refresh());

            /*
             * todo тут не универсально вызывается MosquitoSystemsHelper::updateOrCreateSalary()
             * когда я сделаю
             * 1) интерфейс для таких классов
             * 2) бинд в сервис провайдере
             * тогда исправить
             *
             * по сути, это единственный метод данного класса и подобных
             * ему будущих классов, который вызывается из контроллеров
             */
            MosquitoSystemsHelper::updateOrCreateSalary($product);

            return $product;
        }

        /**
         * Determines if in all product exists at least
         * one product with installation
         *
         * @param Order $order
         * @return bool
         */
        public static function hasInstallation(Order $order): bool {
            return $order->products->contains(function ($product) {
                return ProductHelper::hasInstallation($product);
            });
        }

        /**
         * Calculates salary for all order
         *
         * @param Order $order
         * @return float
         */
        public static function salaries(Order $order): float {
            return $order->salaries->sum('sum');
        }
    }
