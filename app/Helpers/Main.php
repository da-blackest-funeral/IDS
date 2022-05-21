<?php

    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\User;
    use Illuminate\Support\Facades\Route;
    use JetBrains\PhpStorm\ArrayShape;

    /**
     * @return bool
     */
    function isOrderPage(): bool {
        return Route::is('new-order', 'order');
    }

    /**
     * @return bool
     */
    function needPreload(): bool {
        return Route::is('product-in-order');
    }

    /**
     * @return bool
     */
    function updatingOrder(): bool {
        return Str::contains(request()->getRequestUri(), 'orders/') &&
            !Str::contains(request()->getRequestUri(), '/products') &&
            strtolower(request()->input('_method')) == 'put';
    }

    /**
     * @return bool
     */
    function fromUpdatingProductPage(): bool {
        return Route::getRoutes()
                ->match(
                    app('request')
                        ->create(
                            url()->previous()
                        )
                )->getName() == 'product-in-order';
    }

    /**
     * @return bool
     */
    function isMosquitoSystemProduct(): bool {
        $categories = [5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        return in_array(request()->input('categories'), $categories)
            || in_array(request()->input('categoryId'), $categories)
            || requestHasProduct() && in_array(request()->productInOrder->category_id, $categories);
    }

    /**
     * @param string $text
     * @return void
     */
    function notify(string $text) {
        session()->push('notifications', $text);
    }

    /**
     * @param string $text
     * @return void
     */
    function warning(string $text) {
        session()->push('warnings', $text);
    }

    /**
     * @return int|mixed
     */
    function oldProductsCount() {
        try {
            return oldProduct()->count;
        } catch (Exception) {
            return 0;
        }
    }

    /**
     * @param string|null $field
     * @return mixed
     */
    function oldProduct(string $field = null): mixed {
        if (is_null($field)) {
            return session('oldProduct', new stdClass());
        }
        try {
            return session('oldProduct')->$field;
        } catch (Exception) {
            return 0;
        }
    }

    /**
     * @param Order $order
     * @return string
     */
    function delivery(Order $order): string {
        return formatPrice($order->delivery * (1 + $order->additional_visits));
    }

    /**
     * @param object $additional
     * @return bool
     */
    function isInstallation(object $additional): bool {
        return
            str_contains(strtolower($additional->text), 'монтаж') &&
            (int)$additional->price;
    }

    /**
     * @param float|int $first
     * @param float|int $second
     * @return bool
     */
    function equals(float|int $first, float|int $second): bool {
        return strval((float)$first) === strval((float)$second);
    }

    /**
     * @return array
     */
    #[ArrayShape(['data' => "\App\Models\Category[]|\Illuminate\Database\Eloquent\Collection", 'superCategories' => "\Illuminate\Support\Collection", 'installers' => "\App\Models\User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])]
    function dataForOrderPage(): array {
        return [
            'data' => Category::all(),
            'superCategories' => Category::whereIn(
                'id', Category::select(['parent_id'])
                ->whereNotNull('parent_id')
                ->groupBy(['parent_id'])
                ->get()
                ->toArray()
            )->get(),
            'installers' => User::role('installer')->get(),
        ];
    }

    /**
     * @return array
     */
    function selectedGroups() {
        $i = 1;
        $ids = [];
        while (request()->has("group-$i")) {
            $ids[] = request()->input("group-$i");
            $i++;
        }

        return $ids;
    }

    /**
     * @param string $file
     * @return \Illuminate\Support\Collection
     */
    function jsonData(string $file) {
        return collect(
            json_decode(
                file_get_contents(app_path("Services/Config/$file.json"))
            )
        );
    }

    /**
     * @param string $time
     * @param string $format
     * @return string
     */
    function carbon(string $time, string $format): string {
        return \Carbon\Carbon::make($time)->format($format);
    }

    /**
     * @param int $id
     * @return User
     */
    function user(int $id): User {
        Cache::rememberForever('user', fn() => User::findOrFail($id));
        return Cache::get('user');
    }

    /**
     * @param int $id
     * @return string
     */
    function userName(int $id): string {
        return \user($id)->name;
    }

    /**
     * @return bool
     */
    function deletingProduct(): bool {
        return strtolower(request()->input('_method', '')) == 'delete';
    }

    /**
     * @return bool
     */
    function requestHasProduct(): bool {
        return isset(request()->productInOrder);
    }

    /**
     * @return bool
     */
    function requestHasOrder(): bool {
        return isset(request()->order);
    }

    /**
     * @return Order
     */
    function order(): Order {
        return request()->order;
    }

    /**
     * @param Order|null $order
     * @return string
     */
    function orderPrice(Order $order = null): string {
        $order = $order ?? order();
        $minimalSum = systemVariable('minSumOrder');
        if (\OrderHelper::hasInstallation() && $order->price < $minimalSum) {
            return $minimalSum;
        }

        if (!orderHasSale()) {
            return formatPrice($order->price);
        }

        return 'Со скидкой: ' . formatPrice($order->price * (1 - $order->discount / 100));
    }

    /**
     * @return Closure
     */
    function mosquitoInstallationCondition() {
        return function ($product) {
            return mosquitoHasInstallation($product);
        };
    }

    /**
     * @param object $productInOrder
     * @return bool
     */
    function mosquitoHasInstallation(object $productInOrder) {
        return isset($productInOrder->installation_id) &&
            $productInOrder->installation_id &&
            $productInOrder->installation_id != 14;
    }

    /**
     * @param string|null $field
     * @return mixed
     */
    function firstInstaller(?string $field): mixed {
        if (is_null($field)) {
            return User::role('installer')->first();
        }

        return User::role('installer')->first()->$field;
    }

    /**
     * @param string $name
     * @return mixed
     */
    function systemVariable(string $name): mixed {
        return \App\Models\SystemVariables::value($name);
    }

    /**
     * @return bool
     */
    function orderHasSale(): bool {
        return (int) \order()->discount;
    }

    /**
     * @return ProductInOrder
     */
    function requestProduct(): ProductInOrder {
        return request()->productInOrder;
    }

    /**
     * @param int|float $price
     * @return int
     */
    function formatPrice(int|float $price): int {
        return (int)ceil($price);
    }
