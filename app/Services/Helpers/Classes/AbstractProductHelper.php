<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\Interfaces\ProductHelper;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    abstract class AbstractProductHelper implements ProductHelper
    {
        public function hasInstallation(object $productInOrder) {
            return
                isset($productInOrder->installation_id) &&
                $productInOrder->installation_id &&
                $productInOrder->installation_id != 14;
        }

        function make(Order $order) {
            return ProductInOrder::create([
                'installation_id' => Calculator::getInstallation('additional_id'),
                'order_id' => $order->id,
                'name' => Calculator::getProduct()->name(),
                'data' => Calculator::getOptions()->toJson(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                'category_id' => request()->input('categories'),
                'count' => request()->input('count'),
            ]);
        }
    }
