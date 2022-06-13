<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Interfaces\DeliveryService;
    use App\Services\Helpers\Interfaces\MeasuringService;
    use App\Services\Helpers\Interfaces\OrderServiceInterface;
    use App\Services\Repositories\Classes\MosquitoSystemsProductRepository;
    use App\Services\Repositories\Interfaces\ProductRepository;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Calculator\Interfaces\Calculator as CalculatorInterface;

    class OrderService implements OrderServiceInterface
    {
        /**
         * @var ProductRepository
         */
        protected ProductRepository $productRepository;

        /**
         * @param Order $order
         */
        public function __construct(protected Order $order) {
            $this->makeProductRepository();
        }

        protected function makeProductRepository() {
            $this->productRepository = app(
                ProductRepository::class,
                ['products' => $this->order->products]
            );
        }

        /**
         * @return ProductRepository
         */
        public function getProductRepository(): ProductRepository {
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
         * @return OrderServiceInterface
         */
        public function use(Order $order): OrderServiceInterface {
            $this->order = $order;
            $this->makeProductRepository();

            return $this;
        }

        /**
         * @param CreateOrderDto $dto
         * @return Order
         */
        public function make(CreateOrderDto $dto): Order {
            $service = new CreateOrderService();
            return $service->make($dto);
        }

        /**
         * @param CalculatorInterface $calculator
         * @param object $requestData
         * @return Order
         */
        public function create(CalculatorInterface $calculator, object $requestData): Order {
            // todo здесь и в остальных местах сделать dto классы для $requestData
            $dto = new CreateOrderDto();

            $dto->setDeliveryPrice($calculator->getDeliveryPrice())
                ->setUserId($requestData->userId)
                ->setInstallerId($requestData->installerId)
                ->setPrice((int)$calculator->getPrice())
                ->setDiscount(0)
                ->setNeedMeasuring($calculator->getNeedMeasuring())
                ->setMeasuringPrice($calculator->getMeasuringPrice())
                ->setDiscountedMeasuringPrice($calculator->getMeasuringPrice())
                ->setComment($requestData->comment)
                ->setProductCount($calculator->getCount())
                ->setInstallingDifficult($requestData->coefficient)
                ->setIsPrivatePerson($requestData->isPrivatePerson)
                ->setStructure('Пока не готово');

            return $this->make($dto);
        }

        /**
         * @return bool
         * @todo убрать || Calculator::productNeedInstallation()
         *  и вызывать это в клиентском коде
         */
        public function orderOrProductHasInstallation(): bool {
            return !\OrderService::hasProducts() ||
                \OrderService::hasInstallation() ||
                Calculator::productNeedInstallation();
        }

        /**
         * @return void
         */
        public function calculateMeasuringOptions() {
            /** @var MeasuringService $service */
            $service = new MosquitoSystemsMeasuringService(
                $this->order,
                Calculator::productNeedInstallation()
            );

            $service->calculateMeasuringOptions(Calculator::getMeasuringPrice());

            $this->order = $service->getResultOrder();
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
            /** @var DeliveryService $service */
            $service = new DeliveryOrderService(
                $this->order,
                $this->productRepository->maxDelivery()
            );

            $service->calculateDeliveryOptions();

            $this->order = $service->getResultOrder();
        }

        /**
         * Creates new product and adds it to the order
         *
         * @param CalculatorInterface $calculator
         * @param object $requestData
         * @return ProductInOrder
         * @throws \Throwable
         */
        public function addProduct(CalculatorInterface $calculator, object $requestData): ProductInOrder {
            $this->order->price += $calculator->getPrice();

            $this->calculateMeasuringOptions();
            $this->calculateDeliveryOptions();

            $this->order->products_count += $calculator->getCount();

            $this->order->update();
            $product = \ProductService::create($calculator, $requestData);

            \DB::transaction(function () use ($product) {
                \ProductService::use($product)
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
