<?php

    namespace App\Services\Helpers;

    use App\Models\Order;
    // this feature is called real-time facades
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

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

            if (
                static::hasInstallation($order)
                && static::hasProducts($order)
            ) {
                $newProductPrice -= Calculator::getMeasuringPrice();
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

            /*
             * todo баг
             * заключается в том, что при обновлении товара,
             * если уже есть товары с монтажом в заказе, но у самого товара
             * нет монтажа, начисляется лишняя цена за замер.
             */

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

        /**
         * Returns products of order, except
         * old product that are not deleted yet
         *
         * @param Order $order
         * @return Collection
         */
        public static function products(Order $order): Collection {
            return static::withoutOldProduct($order->products);
        }

        /**
         * Rejects not deleted updated product
         *
         * @param Collection $products
         * @return Collection
         */
        protected static function withoutOldProduct(Collection $products): Collection {
            return $products->reject(function ($product) {
                return $product->id == oldProduct('id');
            });
        }

        /**
         * Determines if order has products
         *
         * @param Order $order
         * @return bool
         */
        public static function hasProducts(Order $order): bool {
            return static::products($order)->isNotEmpty();
        }

        /**
         * @param Collection $products
         * @param ProductInOrder $productInOrder
         * @return Collection
         */
        public static function productsWithout(
            Collection $products,
            ProductInOrder $productInOrder
        ): Collection {
            return static::withoutOldProduct($products)
                ->reject(function ($product) use ($productInOrder) {
                    return $product->id == $productInOrder->id;
                });
        }
    }
