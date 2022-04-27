<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;
    use App\Services\Helpers\Interfaces\OrderHelperInterface;

    class OrderHelper implements OrderHelperInterface
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

        public function orderOrProductHasInstallation(Order $order) {
            return !\OrderHelper::hasProducts($order) ||
                \OrderHelper::hasInstallation($order) ||
                Calculator::productNeedInstallation();
        }

        /**
         * @param Order $order
         * @return bool
         */
        protected function notNeedMeasuring(Order $order): bool {
            return $order->measuring_price || $this->hasInstallation($order);
        }

        /**
         * @param Order $order
         * @return void
         */
        protected function deductMeasuringPrice(Order $order) {
            $order->price -= $order->measuring_price;
            $order->measuring_price = 0;
        }

        /**
         * @param Order $order
         * @return void
         */
        protected function calculateMeasuringOptions(Order $order) {
            if ($this->notNeedMeasuring($order)) {
                $order->price -= Calculator::getMeasuringPrice();
                if (Calculator::productNeedInstallation()) {
                    $this->deductMeasuringPrice($order);
                }
                // todo бардак, условие можно написать лучше, уже есть методы для этого
            } elseif (!Calculator::productNeedInstallation()) {
                $order->measuring_price = SystemVariables::value('measuring');
            }
        }

        /**
         * @param Order $order
         * @return void
         */
        protected function calculateDeliveryOptions(Order $order) {
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
        public function addProductTo(Order $order): ProductInOrder {
            $order->price += Calculator::getPrice();

            $this->calculateMeasuringOptions($order);

            $this->calculateDeliveryOptions($order);

            $order->products_count += Calculator::getCount();

            $order->update();

            $product = \ProductHelper::make($order->refresh());

            \ProductHelper::updateOrCreateSalary($product);

            return $product;
        }

        /**
         * Calculates salary for all order
         *
         * @param Order $order
         * @return float
         */
        public function salaries(Order $order): float {
            return $order->salaries->sum('sum');
        }

        /**
         * @param Order $order
         * @return bool
         */
        public function hasInstallation(Order $order): bool {
            return $this->withoutOldProduct($order->products)->contains(function ($product) {
                    return \ProductHelper::hasInstallation($product);
                }) && $this->hasProducts($order);
        }

        /**
         * @param Order $order
         * @return bool
         */
        public function hasProducts(Order $order): bool {
            return $this->withoutOldProduct($order->products)->isNotEmpty();
        }

        /**
         * @param Collection $products
         * @return Collection
         */
        public function withoutOldProduct(Collection $products): Collection {
            return $products->reject(function ($product) {
                return $product->id == oldProduct('id');
            });
        }
    }
