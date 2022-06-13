<?php

    namespace App\Services\Repositories\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Repositories\Interfaces\ProductRepository;
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\Pure;

    class MosquitoSystemsProductRepository extends AbstractProductRepository
    {
        /**
         * @return callable
         */
        protected function maxDeliveryCallback(): callable {
            return function (ProductInOrder $product) {
                return $product->data->delivery->deliveryPrice;
            };
        }

        /**
         * @return callable
         */
        protected function installationCondition(): callable {
            return mosquitoInstallationCondition();
        }
    }
