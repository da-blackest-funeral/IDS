<?php

    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\User;
    use Illuminate\Support\Facades\Route;
    use JetBrains\PhpStorm\ArrayShape;

    function isOrderPage(): bool {
        return Route::is('new-order', 'order');
    }

    function needPreload(): bool {
        return Route::is('product-in-order');
    }

    function updatingOrder(): bool {
        return Str::contains(request()->getRequestUri(), 'orders/') &&
            !Str::contains(request()->getRequestUri(), '/products') &&
            strtolower(request()->input('_method')) == 'put';
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
        $categories = [5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        return in_array(request()->input('categories'), $categories)
            || in_array(request()->input('categoryId'), $categories)
            || requestHasProduct() && in_array(request()->productInOrder->category_id, $categories);
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
        } catch (Exception) {
            return 0;
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

    function isInstallation(object $additional): bool {
        return
            str_contains(strtolower($additional->text), 'монтаж') &&
            (int)$additional->price;
    }

    function equals(float|int $first, float|int $second) {
        return strval((float)$first) === strval((float)$second);
    }

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
                file_get_contents(app_path("Services/Config/$file.json"))
            )
        );
    }

    function carbon(string $time, string $format): string {
        return \Carbon\Carbon::make($time)->format($format);
    }

    /**
     * @param int $id
     * @return User
     */
    function user(int $id): User {
        return User::findOrFail($id);
    }

    /**
     * @param int $id
     * @return string
     */
    function userName(int $id): string {
        return \user($id)->name;
    }

    function deletingProduct(): bool {
        return strtolower(request()->input('_method', '')) == 'delete';
    }

    function requestHasProduct(): bool {
        return isset(request()->productInOrder);
    }

    function requestHasOrder(): bool {
        return isset(request()->order);
    }

    function order(): Order {
        return request()->order;
    }

    function orderPrice(): string {
        if (!orderHasSale()) {
            return formatPrice(\order()->price);
        }

        return 'Со скидкой: ' . formatPrice(\order()->discounted_price);
    }

    function orderHasSale(): bool {
        return \order()->price != \order()->discounted_price;
    }

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
