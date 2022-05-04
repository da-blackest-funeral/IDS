<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use App\Services\Repositories\Classes\ProductRepository;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Interfaces\OrderHelperInterface;

    class OrderHelper implements OrderHelperInterface
    {
        /**
         * @param Order $order
         */
        public function __construct(protected Order $order) {
        }

        /**
         * @return Order
         */
        public function getOrder(): Order {
            return $this->order;
        }

        /**
         * @param Order $order
         * @return OrderHelperInterface
         */
        public function use(Order $order): OrderHelperInterface {
            $this->order = $order;

            return $this;
        }

        public function make(): Order {
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

        public function orderOrProductHasInstallation(): bool {
            return !\OrderHelper::hasProducts() ||
                \OrderHelper::hasInstallation() ||
                Calculator::productNeedInstallation();
        }

        protected function notNeedMeasuring(): bool {
            return $this->order->measuring_price || $this->hasInstallation();
        }

        protected function deductMeasuringPrice() {
            $this->order->price -= $this->order->measuring_price;
            $this->order->measuring_price = 0;
        }

        protected function calculateMeasuringOptions() {
            if ($this->notNeedMeasuring()) {
                $this->order->price -= Calculator::getMeasuringPrice();
                if (Calculator::productNeedInstallation()) {
                    $this->deductMeasuringPrice();
                }
            } elseif (!Calculator::productNeedInstallation()) {
                $this->order->measuring_price = SystemVariables::value('measuring');
            }
        }

        protected function calculateDeliveryOptions() {
            if ($this->order->delivery) {
                $this->order->price -= min(
                    $this->order->delivery,
                    Calculator::getDeliveryPrice()
                );

                $this->order->delivery = max(
                    Calculator::getDeliveryPrice(),
                    $this->order->delivery
                );
            }
        }

        public function removeFromOrder(ProductInOrder $productInOrder) {
            $this->order->price -= $productInOrder->data->main_price;
            $this->order->products_count -= $productInOrder->count;

            foreach ($productInOrder->data->additional as $additional) {
                $this->order->price -= $additional->price;
            }

            $this->order->update();
        }

        /**
         * Creates new product and adds it to the order
         *
         * @return ProductInOrder
         */
        public function addProduct(): ProductInOrder {
            $this->order->price += Calculator::getPrice();

            $this->calculateMeasuringOptions();

            $this->calculateDeliveryOptions();

            $this->order->products_count += Calculator::getCount();

            $this->order->update();

            $product = \ProductHelper::make();

            \ProductHelper::use($product)
                ->updateOrCreateSalary();

            return $product;
        }

        /**
         * Calculates salary for all order
         *
         * @return float
         */
        public function salaries(): float {
            return $this->order->salaries->sum('sum');
        }

        /**
         * @return bool
         */
        public function hasInstallation(): bool {
            return ProductRepository::use($this->order->products)
                    ->without(oldProduct())
                    ->get()
                    ->contains(function ($product) {
                    return \ProductHelper::hasInstallation($product);
                }) && $this->hasProducts();
        }

        /**
         * @return bool
         */
        public function hasProducts(): bool {
            return ProductRepository::use($this->order->products)
                ->without(oldProduct())
                ->isNotEmpty();
        }
    }
