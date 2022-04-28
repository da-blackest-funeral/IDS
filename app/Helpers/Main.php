<?php

    use App\Models\Order;
    use Illuminate\Support\Facades\Route;

    function isOrderPage(): bool {
        return Route::is('new-order', 'order');
    }

    function needPreload(): bool {
        return Route::is('product-in-order');
    }

    function fromUpdatingProductPage(): bool {
        return Route::getRoutes()
                ->match(
                    app('request')
                        ->create(
                            url()->previous()
                        )
                )->getName() == 'product-in-order';
    }

    function isMosquitoSystemProduct(): bool {
        return in_array(request()->input('categories'), [5, 6, 7, 8, 9, 10, 11, 12, 13, 14])
        || in_array(request()->input('categoryId'), [5, 6, 7, 8, 9, 10, 11, 12, 13, 14])
        || in_array(request()->productInOrder->category_id, [5, 6, 7, 8, 9, 10, 11, 12, 13, 14]);
    }

    function notify($text) {
        session()->push('notifications', $text);
    }

    // todo перенести это в класс
    function productAlreadyExists($calculator, $product) {
        return json_decode(
                $calculator->getOptions()
                    ->except(['main_price', 'salary', 'measuring', 'delivery'])
                    ->toJson()
            ) == json_decode(
                collect(json_decode($product->data))
                    ->except(['main_price', 'salary', 'measuring', 'delivery'])
                    ->toJson()
            );
    }

    // todo перенести это в класс
    function updateProductInOrder($product, $mainPrice) {
        $product->count += (int)request()->input('count');
        $data = json_decode($product->data);
        $data->main_price += $mainPrice;
        $product->data = json_encode($data);
        $product->update();
    }

    function warning(string $text) {
        session()->push('warnings', $text);
    }

    // todo перенести это в класс
    function orderSalaries(Order $order) {
        return $order->salaries->sum('sum');
    }

    // when updating products, we save
    // count of products that was before update
    // todo перенести это в класс
    function oldProductsCount() {
        try {
            return oldProduct()->count;
        } catch (Exception $e) {
            Debugbar::info($e->getMessage());
            return 0;
        }
    }

    // todo перенести это в класс
    function oldProduct(string $field = null) {
        if (is_null($field)) {
            return session('oldProduct', new stdClass());
        }
        try {
            return session('oldProduct')->$field;
        } catch (Exception) {
            return 0;
        }
    }

    // todo перенести это в класс
    function oldProductHasInstallation(): bool {
        return ProductHelper::hasInstallation(oldProduct());
    }

    function isInstallation(object $additional): bool {
        return
            str_contains(strtolower($additional->text), 'монтаж') &&
            (int)$additional->price;
    }

    function equals(float|int $first, float|int $second) {
        return strval((float)$first) === strval((float)$second);
    }

    function selectedGroups() {
        $i = 1;
        $ids = [];
        while (request()->has("group-$i")) {
            $ids[] = request()->input("group-$i");
            $i++;
        }

        return $ids;
    }

    function jsonData(string $file) {
        return collect(
            json_decode(
                file_get_contents(
                    app_path("Services/Config/$file.json")
                )
            )
        );
    }
