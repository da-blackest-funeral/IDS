<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\Interfaces\ProductHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    abstract class AbstractProductHelper implements ProductHelperInterface
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
                'data' => Calculator::getOptions(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                'category_id' => request()->input('categories'),
                'count' => request()->input('count'),
            ]);
        }

        protected function productsWithInstallation(ProductInOrder $productInOrder): Collection {
            return $productInOrder->order
                ->products()
                ->where('category_id', request()->input('categories'))
                ->whereNotIn('installation_id', [0, 14])
                ->get();
        }

        public function countProductsWithInstallation(ProductInOrder $productInOrder): int {
            return $this->countOf(
                $this->productsWithInstallation($productInOrder)
            );
        }

        public function countOf(Collection $products): int {
            return $products->sum('count');
        }

        public function productHasCoefficient(ProductInOrder $productInOrder): bool {
            return $productInOrder->data->coefficient > 1;
        }
    }
