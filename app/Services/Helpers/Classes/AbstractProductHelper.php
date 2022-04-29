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
         * @param object $productInOrder
         * @return bool
         */
        public function hasInstallation(object $productInOrder): bool {
            return
                isset($productInOrder->installation_id) &&
                $productInOrder->installation_id &&
                $productInOrder->installation_id != 14;
        }

        /**
         * @param Order $order
         * @return ProductInOrder
         */
        function make(Order $order): ProductInOrder {
            return ProductInOrder::create([
                'installation_id' => Calculator::getInstallation('additional_id'),
                'order_id' => $order->id,
                'name' => Calculator::getProduct()->name(),
                'data' => Calculator::getOptions(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                'category_id' => request()->input('categories'),
                'count' => request()->input('count'),
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
    }
