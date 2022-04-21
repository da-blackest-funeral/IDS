<?php

    namespace App\Services\Helpers;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    class ProductHelper
    {
        /**
         * Creates a new product
         *
         * @param Order $order
         * @return ProductInOrder
         */
        public static function make(Order $order): ProductInOrder {
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

        /**
         * Gets count of products from collection
         *
         * @param Collection $products
         * @return float
         */
        public static function countOfProducts(Collection $products): float {
            return $products->sum('count');
        }

        /**
         * Determines if same product already exists in order
         *
         * @param ProductInOrder $product
         * @return bool
         */
        public static function exists(ProductInOrder $product): bool {
            return json_decode(
                    Calculator::getOptions()
                        ->except(['main_price', 'salary', 'measuring', 'delivery'])
                        ->toJson()
                ) == json_decode(
                    collect(json_decode($product->data))
                        ->except(['main_price', 'salary', 'measuring', 'delivery'])
                        ->toJson()
                );
        }

        /**
         * Updates product
         *
         * @param ProductInOrder $product
         * @param int $mainPrice
         * @return ProductInOrder
         */
        public static function update(ProductInOrder $product, int $mainPrice): ProductInOrder {
            $product->count += (int)request()->input('count');

            $data = json_decode($product->data);
            $data->main_price += $mainPrice;

            $product->data = json_encode($data);
            $product->update();

            return $product;
        }

        /**
         * Determines if product needs in installation
         *
         * @param ProductInOrder $productInOrder
         * @return bool
         */
        public static function hasInstallation(ProductInOrder $productInOrder): bool {
            return
                isset($productInOrder->installation_id) &&
                $productInOrder->installation_id &&
                $productInOrder->installation_id != 14;
        }

        /**
         * When product's updating occurs, determine if
         * it had installation before it have been updated
         *
         * @return bool
         */
        public static function oldProductHasInstallation(): bool {
            return static::hasInstallation(oldProduct());
        }

        /**
         * Determines of product has coefficient of difficulty
         *
         * @param ProductInOrder $productInOrder
         * @return bool
         */
        public static function productHasCoefficient(ProductInOrder $productInOrder) {
            return static::productData($productInOrder, 'coefficient') > 1;
        }

        /**
         * Getting product data from json format
         *
         * @param ProductInOrder $productInOrder
         * @param string|null $field
         * @return mixed
         */
        public static function productData(ProductInOrder $productInOrder, string $field = null): mixed {
            try {
                if (is_null($field)) {
                    return json_decode($productInOrder->data);
                }
                return json_decode($productInOrder->data)->$field;
            } catch (\Exception) {
                return new \stdClass();
            }
        }

            /**
             * Getting count of products in current order
             * that has installation
             *
             * @param ProductInOrder $productInOrder
             * @return int
             */
            public
            static function countProductsWithInstallation(ProductInOrder $productInOrder): int {
                return static::countOfProducts(
                    static::productsWithInstallation($productInOrder)
                );
            }

            /**
             * Getting all products that has installation in current order
             *
             * @param ProductInOrder $productInOrder
             * @return Collection
             */
            public
            static function productsWithInstallation(ProductInOrder $productInOrder): Collection {
                return $productInOrder->order
                    ->products()
                    ->where('category_id', request()->input('categories'))
                    ->whereNotIn('installation_id', [0, 14])
                    ->get();
            }
        }
