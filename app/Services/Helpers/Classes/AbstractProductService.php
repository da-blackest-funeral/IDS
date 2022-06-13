<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\Interfaces\ProductServiceInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Calculator\Interfaces\Calculator as CalculatorInterface;
    use Illuminate\Support\Collection;

    abstract class AbstractProductService implements ProductServiceInterface
    {
        /**
         * @var Collection
         */
        protected Collection $products;

        /**
         * @param ProductInOrder $productInOrder
         * @param Order $order
         */
        public function __construct(
            protected ProductInOrder $productInOrder,
            protected Order          $order,
        ) {
        }

        /**
         * @return ProductInOrder
         */
        public function getProduct(): ProductInOrder {
            return $this->productInOrder;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return ProductServiceInterface
         */
        public function use(ProductInOrder $productInOrder): ProductServiceInterface {
            $this->productInOrder = $productInOrder;
            $this->order = $productInOrder->order;
            $this->products = $this->order->products;

            return $this;
        }

        /**
         * @param CreateProductDto $dto
         * @return ProductInOrder
         */
        public function make(CreateProductDto $dto): ProductInOrder {
            $service = new CreateProductService();
            return $service->make($dto);
        }

        /**
         * @param CalculatorInterface $calculator
         * @param object $requestData
         * @return ProductInOrder
         */
        public function create(CalculatorInterface $calculator, object $requestData) {
            $dto = new CreateProductDto();
            $dto->setUserId($requestData->userId)
                ->setComment($requestData->comment)
                ->setCategoryId($requestData->categoryId)
                ->setCount($requestData->count)
                ->setData($calculator->getOptions())
                ->setInstallationId($calculator->getInstallation('additional_id'))
                ->setName($calculator->getProduct()->name())
                ->setOrderId($requestData->orderId);

            return $this->make($dto);
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return bool
         */
        public function productHasCoefficient(ProductInOrder $productInOrder): bool {
            return $productInOrder->data->coefficient > 1;
        }

        /**
         * @return bool
         */
        public function noInstallation(): bool {
            return !\OrderService::hasInstallation() &&
                !Calculator::productNeedInstallation();
        }

        abstract public function installationCondition(): callable;
    }
