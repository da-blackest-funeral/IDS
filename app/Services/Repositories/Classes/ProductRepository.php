<?php

    namespace App\Services\Repositories\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Repositories\Interfaces\ProductRepositoryInterface;
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\Pure;

    class ProductRepository implements ProductRepositoryInterface
    {
        public function __construct(protected Collection $products) {
        }

        public static function use(Collection $products): ProductRepositoryInterface {
            $instance = new static(collect());
            $instance->products = $products;

            return $instance;
        }

        public function maxDelivery(): int|null {
            return $this->products->max(function (ProductInOrder $product) {
               return $product->data->delivery->deliveryPrice;
            });
        }

        public function get(): Collection {
            return $this->products;
        }

        public static function byCategory(ProductInOrder $productInOrder): ProductRepositoryInterface {
            $instance = new static(collect());
            $instance->products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id)
                ->get();

            return $instance;
        }

        public static function byCategoryWithout(ProductInOrder $productInOrder): ProductRepositoryInterface {
            return static::byCategory($productInOrder)->without($productInOrder);
        }

        public function has(callable $callback): bool {
            return $this->products->contains($callback);
        }

        public function hasInstallation(): bool {
            return $this->has(\ProductHelper::installationCondition());
        }

        public function onlyWithInstallation(): ProductRepositoryInterface {
            $this->products = $this->products->filter(
                \ProductHelper::installationCondition()
            );

            return $this;
        }

        public function without(object $productToReject): ProductRepositoryInterface {
            $this->products =
                $this->products->reject(function ($product) use ($productToReject) {
                    return isset($productToReject->id) && $product->id == $productToReject->id;
                });

            return $this;
        }

        public static function reject(Collection $products, object $productToReject): Collection {
            return static::use($products)
                ->without($productToReject)
                ->get();
        }

        public function count(): int {
            return $this->products->sum('count');
        }

        #[Pure] public function isEmpty(): bool {
            return $this->products->isEmpty();
        }

        public function isNotEmpty(): bool {
            return $this->products->isNotEmpty();
        }

        public static function withInstallation(Order $order, int $categoryId = null) {
            $instance = new static(collect());
            $instance->products = $order->products()
                ->where('category_id', $categoryId ?? request()->input('categories'))
                ->whereNotIn('installation_id', [0, 14])
                ->get();

            return $instance;
        }

        public function first() {
            return $this->products->first();
        }
    }
