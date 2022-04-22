<?php

    namespace App\Services\Helpers;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    // this feature is called real-time facades

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
         * @param Order $order
         * @return bool
         */
        protected static function notNeedMeasuring(Order $order): bool {
            return $order->measuring_price || static::hasInstallation($order);
        }

        /**
         * @param Order $order
         * @return void
         */
        protected static function deductMeasuringPrice(Order $order) {
            $order->price -= $order->measuring_price;
            $order->measuring_price = 0;
        }

        /**
         * @param Order $order
         * @return void
         */
        protected static function calculateMeasuringOptions(Order $order) {
            if (static::notNeedMeasuring($order)) {
                $order->price -= Calculator::getMeasuringPrice();
                if (Calculator::productNeedInstallation()) {
                    static::deductMeasuringPrice($order);
                }
            }
        }

        /**
         * @param Order $order
         * @return void
         */
        protected static function calculateDeliveryOptions(Order $order) {
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
        }

        /**
         * Creates new product and adds it to the order
         *
         * @param Order $order
         * @return ProductInOrder
         */
        public static function addProduct(Order $order): ProductInOrder {
            $order->price += Calculator::getPrice();

            static::calculateMeasuringOptions($order);

            static::calculateDeliveryOptions($order);

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
         * Determines if in all order exists at least
         * one product with installation
         *
         * @param Order $order
         * @return bool
         */
        public static function hasInstallation(Order $order): bool {
            return static::products($order)->contains(function ($product) {
                    return ProductHelper::hasInstallation($product);
                }) && static::hasProducts($order);
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
        public static function withoutOldProduct(Collection $products): Collection {
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
            Collection     $products,
            ProductInOrder $productInOrder
        ): Collection {
            return static::withoutOldProduct($products)->reject(function ($product) use ($productInOrder) {
                    return $product->id == $productInOrder->id;
                });
        }

        /**
         * @param Order $order
         * @param ProductInOrder $productInOrder
         * @return void
         */
        public static function reducePrice(Order $order, ProductInOrder $productInOrder) {
            $productData = json_decode($productInOrder->data);
            $order->price -= $productData->main_price;
            $order->products_count -= $productInOrder->count;

            foreach ($productData->additional as $additional) {
                $order->price -= $additional->price;
            }
        }

        public static function addMeasuringPrice(Order $order) {
            $order->measuring_price = Calculator::getMeasuringPrice();
            $order->price += $order->measuring_price;
        }

        public static function needAddMeasuring(Order $order) {
            return !static::hasInstallation($order) &&
                !Calculator::productNeedInstallation() &&
                // todo заменить на интерфейс
                MosquitoSystemsHelper::oldProductHasInstallation();
        }
    }
