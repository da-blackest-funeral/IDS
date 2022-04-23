<?php

    // todo возможно вообще сделать фасады для всех своих хелперов
    // чтобы определить четкий интерфейс для их использования
    // тем более что они не реюзабельны, можно сделать фасад для москитных систем, для стеклопакетов и т.д.
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;

    // this feature is called real-time facades
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Facades\Route;

    require_once 'MosquitoSystems.php';

    function isOrderPage() {
        return Route::is('new-order', 'order');
    }

    function fromUpdatingProductPage() {
        return Route::getRoutes()
                ->match(
                    app('request')
                        ->create(
                            url()->previous()
                        )
                )->getName() == 'product-in-order';
    }

    function notify($text) {
        session()->push('notifications', $text);
    }

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

    function orderSalaries(Order $order) {
        return $order->salaries->sum('sum');
    }

    // when updating products, we save
    // count of products that was before update
    function oldProductsCount() {
        try {
            return oldProduct()->count;
        } catch (Exception $e) {
            Debugbar::info($e->getMessage());
            return 0;
        }
    }

    function oldProductData(string|array $field = null) {
        if (is_null($field)) {
            return json_decode(oldProduct('data'));
        } elseif (is_string($field)) {
            return json_decode(oldProduct('data'))->$field;
        } elseif (is_array($field)) {
            $result = json_decode(oldProduct('data'));
            foreach ($field as $item) {
                $result = $result->$item;
            }

            return $result;
        }
    }

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

    function oldProductHasInstallation(): bool {
        return \ProductHelper::hasInstallation(oldProduct());
    }

    function isInstallation(object $additional): bool {
        return
            str_contains(strtolower($additional->text), 'монтаж') &&
            (int)$additional->price;
    }

    function equals(float|int $first, float|int $second) {
        return strval($first) === strval($second);
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
