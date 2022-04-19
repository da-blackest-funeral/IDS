<?php

    use App\Models\Category;
    use App\Models\Order;
    use App\Models\User;

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

    function warning(string $text) {
        session()->push('warnings', $text);
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
            return session('oldProduct');
        }

        try {
            return session('oldProduct')->$field;
        } catch (Exception $exception) {
            return 0;
        }
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

    function newOrderData(): array {
        return [
            'data' => Category::all(),
            'superCategories' => Category::whereIn(
                'id', Category::select(['parent_id'])
                ->whereNotNull('parent_id')
                ->groupBy(['parent_id'])
                ->get()
                ->toArray()
            )->get(),
            'orderNumber' => Order::count() + 1,
            'installers' => User::role('installer')->get()
        ];
    }

    function orderData(Order $order): array {
        $result = newOrderData();
        $result['products'] = $order->products;

        return $result;
    }
