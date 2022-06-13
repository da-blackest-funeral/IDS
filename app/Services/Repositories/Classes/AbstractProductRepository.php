<?php

    namespace App\Services\Repositories\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Repositories\Interfaces\ProductRepository;
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\Pure;

    abstract class AbstractProductRepository implements ProductRepository
    {
        /**
         * @param Collection $products
         */
        public function __construct(protected Collection $products) {
        }

        /**
         * @param Collection $products
         * @return ProductRepository
         */
        public static function use(Collection $products): ProductRepository {
            $instance = new static(collect());
            $instance->products = $products;

            return $instance;
        }

        /**
         * @return int
         */
        public function maxDelivery(): int {
            return $this->hasManyProducts() ?
                $this->maxDeliveryWithoutOld() :
                $this->products->max($this->maxDeliveryCallback());
        }

        /**
         * @return bool
         */
        #[Pure] protected function hasManyProducts(): bool {
            return $this->products->count() > 1;
        }

        /**
         * @return mixed
         */
        protected function maxDeliveryWithoutOld() {

            return $this->remove(oldProduct())
                ->get()
                ->max($this->maxDeliveryCallback());
        }

        /**
         * @return Collection
         */
        public function get(): Collection {
            return $this->products;
        }

        // todo сделать чтобы принимались параметры $categoryId, $orderId

        /**
         * @param ProductInOrder $productInOrder
         * @return ProductRepository
         */
        public static function byCategory(ProductInOrder $productInOrder): ProductRepository {
            $instance = new static(collect());
            $instance->products = ProductInOrder::whereCategoryId($productInOrder->category_id)
                ->whereOrderId($productInOrder->order_id)
                ->get();

            return $instance;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return ProductRepository
         */
        public static function byCategoryWithout(ProductInOrder $productInOrder): ProductRepository {
            return static::byCategory($productInOrder)->remove($productInOrder);
        }

        /**
         * @param Collection $products
         * @param object $productToReject
         * @return Collection
         *
         * @todo метод выглядит бесполезным
         */
        public static function reject(Collection $products, object $productToReject): Collection {

            return static::use($products)
                ->remove($productToReject)
                ->get();
        }

        /**
         * @return int
         */
        public function count(): int {
            return $this->products->sum('count');
        }

        /**
         * @return bool
         */
        #[Pure] public function isEmpty(): bool {
            return $this->products->isEmpty();
        }

        /**
         * @return bool
         */
        public function isNotEmpty(): bool {
            return $this->products->isNotEmpty();
        }

        // todo сделать параметр $categoryId обязательным

        /**
         * @param Order $order
         * @param int|null $categoryId
         * @return static
         */
        public static function withInstallation(Order $order, int $categoryId = null) {
            $instance = new static(collect());
            $instance->products = $order->products()
                ->where('category_id', $categoryId ?? request()->input('categories'))
                ->whereNotIn('installation_id', [0, 14])
                ->get();

            return $instance;
        }

        /**
         * @return mixed
         */
        public function first() {
            return $this->products->first();
        }

        /**
         * @param callable $callback
         * @return bool
         */
        public function has(callable $callback): bool {
            return $this->products->contains($callback);
        }

        /**
         * @return bool
         */
        public function hasInstallation(): bool {
            return $this->has($this->installationCondition());
        }

        /**
         * @return ProductRepository
         */
        public function onlyWithInstallation(): ProductRepository {
            $this->products = $this->products->filter(
                $this->installationCondition()
            );


            return $this;
        }

        /**
         * @param object $productToReject
         * @return ProductRepository
         */
        public function remove(object $productToReject): ProductRepository {
            $this->products =
                $this->products->reject(function ($product) use ($productToReject) {
                    return isset($productToReject->id) && $product->id == $productToReject->id;
                });

            return $this;
        }

        /**
         * @param object $productToReject
         * @return ProductRepository
         */
        public function without(object $productToReject): ProductRepository {
            $instance = new static($this->products);
            return $instance->remove($productToReject);
        }

        /**
         * @return callable
         */
        abstract protected function maxDeliveryCallback(): callable;

        /**
         * @return callable
         */
        abstract protected function installationCondition(): callable;
    }
