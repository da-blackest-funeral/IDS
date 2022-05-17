<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\Interfaces\ProductHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    abstract class AbstractProductHelper implements ProductHelperInterface
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
         * @return ProductHelperInterface
         */
        public function use(ProductInOrder $productInOrder): ProductHelperInterface {
            $this->productInOrder = $productInOrder;
            $this->order = $productInOrder->order;
            $this->products = $this->order->products;

            return $this;
        }

        /**
         * @param object $productInOrder
         * @return bool
         */
        public static function hasInstallation(object $productInOrder): bool {
            return mosquitoHasInstallation($productInOrder);
        }

        /**
         * @return ProductInOrder
         */
        function make(): ProductInOrder {
            $order = \OrderHelper::getOrder();
            return ProductInOrder::create([
                'installation_id' => Calculator::getInstallation('additional_id'),
                'order_id' => $order->id,
                'name' => Calculator::getProduct()->name(),
                'data' => Calculator::getOptions(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                'category_id' => request()->input('categories'),
                'count' => request()->input('count', 1),
                'comment' => request()->input('comment') ?? 'Нет комментария',
            ]);
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
            return !\OrderHelper::hasInstallation() &&
                !Calculator::productNeedInstallation();
        }

        abstract public function installationCondition(): callable;
    }
