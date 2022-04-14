<?php

    namespace App\Services\Helpers;

    use App\Models\MosquitoSystems\Type;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    class ProductHelper
    {
        public static function make(Order $order) {
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

        public static function countOfProducts(Collection $products) {
            return $products->sum('count');
        }

        public static function exists(ProductInOrder $product) {
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

        public static function update(ProductInOrder $product, int $mainPrice) {
            $product->count += (int)request()->input('count');
            $data = json_decode($product->data);
            $data->main_price += $mainPrice;
            $product->data = json_encode($data);
            $product->update();
        }

        public static function hasInstallation(ProductInOrder $productInOrder) {
            return
                isset($productInOrder->installation_id) &&
                $productInOrder->installation_id &&
                $productInOrder->installation_id != 14;
        }

        public static function oldProductHasInstallation(): bool {
            return static::hasInstallation(oldProduct());
        }

        public static function productHasCoefficient(ProductInOrder $productInOrder) {
            return static::productData($productInOrder, 'coefficient') > 1;
        }

        public static function productData(ProductInOrder $productInOrder, string $field = null) {
            if (is_null($field)) {
                return json_decode($productInOrder->data);
            }

            return json_decode($productInOrder->data)->$field;
        }

        public static function countProductsWithInstallation(ProductInOrder $productInOrder): int {
            return static::countOfProducts(
                static::productsWithInstallation($productInOrder)
            );
        }

        /*
         * todo улучшение кода
         * когда буду рефакторить, надо сделать так, чтобы пропускался старый товар (при обновлении, который еще не удален)
         * во всех местах где используется этот метод нужно это учесть
         */
        public static function productsWithInstallation(ProductInOrder $productInOrder): Collection {
            return $productInOrder->order
                ->products()
                ->where('category_id', request()->input('categories'))
                ->whereNotIn('installation_id', [0, 14])
                ->get();
        }
    }
