<?php

    namespace App\Services\Repositories\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Repositories\Interfaces\ProductRepositoryInterface;
    use Illuminate\Support\Collection;

    class ProductRepository implements ProductRepositoryInterface
    {
        protected Collection $products;

        public static function use(Collection $products): ProductRepositoryInterface {
            $instance = new static();
            $instance->products = $products;

            return $instance;
        }

        public function get(): Collection {
            return $this->products;
        }

        public static function byCategory(ProductInOrder $productInOrder): ProductRepositoryInterface {
            $instance = new static();
            $instance->products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id)
                ->get();

            return $instance;
        }

        public function without(object $productToReject): ProductRepositoryInterface {
            $this->products =
                $this->products->reject(function ($product) use ($productToReject) {
                    return isset($productToReject->id) && $product->id == $productToReject->id;
                });

            return $this;
        }

        public function count(): int {
            return $this->products->sum('count');
        }

        public function isNotEmpty(): bool {
            return $this->products->isNotEmpty();
        }

        public static function withInstallation(Order $order) {
            $instance = new static();
            $instance->products = $order->products()
                ->where('category_id', request()->input('categories'))
                ->whereNotIn('installation_id', [0, 14])
                ->get();

            return $instance;
        }
    }
