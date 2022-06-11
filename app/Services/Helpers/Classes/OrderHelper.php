<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Interfaces\OrderHelperInterface;
    use App\Services\Repositories\Interfaces\ProductRepositoryInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class OrderHelper implements OrderHelperInterface
    {
        /**
         * @var ProductRepositoryInterface
         */
        protected ProductRepositoryInterface $productRepository;

        /**
         * @param Order $order
         */
        public function __construct(protected Order $order) {
            $this->makeProductRepository();
        }

        // todo тоже сделать фабричный метод
        protected function makeProductRepository() {
            $this->productRepository = app(
                ProductRepositoryInterface::class,
                ['products' => $this->order->products]
            );
        }

        /**
         * @return ProductRepositoryInterface
         */
        public function getProductRepository(): ProductRepositoryInterface {
            return $this->productRepository;
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
            $this->makeProductRepository();

            return $this;
        }

        /**
         * @return Order
         */
        public function make(): Order {
            return Order::create([
                'delivery' => Calculator::getDeliveryPrice(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                // todo убрать этот кошмар
                'installer_id' => firstInstaller('id'),
                'price' => Calculator::getPrice(),
                'discount' => 0,
                'measuring' => Calculator::getNeedMeasuring(),
                'measuring_price' => Calculator::getMeasuringPrice(),
                'discounted_measuring_price' => Calculator::getMeasuringPrice(),
                'comment' => request()->input('comment') ?? 'Комментарий отсутствует',
                'products_count' => Calculator::getCount(),
                'installing_difficult' => request()->input('coefficient'),
                'is_private_person' => request()->input('person') == 'physical',
                'structure' => 'Пока не готово',
            ]);
        }

        /**
         * @return bool
         */
        public function orderOrProductHasInstallation(): bool {
            return !\OrderHelper::hasProducts() ||
                \OrderHelper::hasInstallation() ||
                Calculator::productNeedInstallation();
        }

        /**
         * @return bool
         */
        protected function notNeedMeasuring(): bool {
            return $this->order->measuring_price || $this->hasInstallation();
        }

        /**
         * @return void
         */
        protected function deductMeasuringPrice() {
            $this->order->price -= $this->order->measuring_price;
            $this->order->measuring_price = 0;
        }

        /**
         * @return void
         */
        public function calculateMeasuringOptions() {
            $this->notNeedMeasuring() ? $this->removeMeasuring() : $this->restoreMeasuring();
        }

        /**
         * @return void
         */
        protected function removeMeasuring() {
            $this->order->price -= Calculator::getMeasuringPrice();
            if (Calculator::productNeedInstallation()) {
                $this->deductMeasuringPrice();
            }
        }

        /**
         * @return void
         */
        protected function restoreMeasuring() {
            if (!Calculator::productNeedInstallation() || deletingProduct()) {
                $this->order->measuring_price = SystemVariables::value('measuring');
            }

            if (deletingProduct()) {
                $this->order->price += $this->order->measuring_price;
            }
        }

        /**
         * @return void
         */
        protected function decreasePriceByDelivery() {
            if (!deletingProduct()) {
                $this->order->price -= min(
                    $this->order->delivery,
                    Calculator::getDeliveryPrice()
                );
            } else {
                $this->deliveryWhenDeletingProduct();
            }
        }

        /**
         * @return void
         */
        protected function deliveryWhenDeletingProduct() {
            $this->order->price -= max(
                $this->order->delivery,
                $this->productRepository->maxDelivery()
            );

            $this->order->price += $this->productRepository->maxDelivery();
        }

        /**
         * @return void
         */
        protected function determineMaxDelivery() {
            $this->order->delivery = $this->order->need_delivery ? max(
                Calculator::getDeliveryPrice(),
                $this->productRepository->maxDelivery()
            ) : 0;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return void
         */
        public function remove(ProductInOrder $productInOrder) {
            $this->order->price -= $productInOrder->data->main_price;
            $this->order->products_count -= $productInOrder->count;

            foreach ($productInOrder->data->additional as $additional) {
                $this->order->price -= $additional->price;
            }

            session()->put('oldProduct', $productInOrder);
            $this->order->update();
        }

        /**
         * @return void
         */
        public function calculateDeliveryOptions() {
            if ($this->order->need_delivery) {
                $this->decreasePriceByDelivery();
                $this->determineMaxDelivery();
            }
        }

        /**
         * Creates new product and adds it to the order
         *
         * @return ProductInOrder
         * @throws \Throwable
         */
        public function addProduct(): ProductInOrder {

            $this->order->price += Calculator::getPrice();

            $this->calculateMeasuringOptions();
            $this->calculateDeliveryOptions();

            $this->order->products_count += Calculator::getCount();

            $this->order->update();
            $product = \ProductHelper::make();

            \DB::transaction(function () use ($product) {
                \ProductHelper::use($product)
                    ->updateOrCreateSalary();
            });

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
            return $this->productRepository
                    ->without(oldProduct())
                    ->hasInstallation()
                && $this->hasProducts();
        }

        /**
         * @return bool
         */
        public function hasProducts(): bool {
            return $this->productRepository
                ->isNotEmpty();
        }
    }
