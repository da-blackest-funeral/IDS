<?php

    namespace App\Services\Repositories\Classes;

    use App\Models\ProductInOrder;
    use App\Services\Repositories\Interfaces\ProductRepositoryInterface;
    use Illuminate\Support\Collection;

    class ProductRepository implements ProductRepositoryInterface
    {
        protected Collection $products;

        public function use(Collection $products): ProductRepositoryInterface {
            $this->products = $products;
            return $this;
        }

        public function get() {
            return $this->products;
        }

        public function byCategory(ProductInOrder $productInOrder): ProductRepositoryInterface {
            $this->products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id)
                ->get();

            return $this;
        }

        public function without(ProductInOrder $productInOrder): ProductRepositoryInterface {
            $this->products =
                $this->products->reject(function ($product) use ($productInOrder) {
                    return $product->id == $productInOrder->id;
                });

            return $this;
        }

        public function count(): int {
            return $this->products->sum('count');
        }

        public function isNotEmpty(): bool {
            return $this->products->isNotEmpty();
        }
    }
