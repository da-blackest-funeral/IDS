<?php

    // todo возможно вообще сделать фасады для всех своих хелперов
    // чтобы определить четкий интерфейс для их использования
    // тем более что они не реюзабельны, можно сделать фасад для москитных систем, для стеклопакетов и т.д.
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    // this feature called real-time facades
//    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
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

    function createOrder() {
        return Order::create([
            'delivery' => Calculator::getDeliveryPrice(),
            'user_id' => auth()->user()->getAuthIdentifier(),
            'installer_id' => request()->input('installer') ?? 2,
            'price' => Calculator::getPrice(),
            'discounted_price' => Calculator::getPrice(), // todo сделать расчет с учетом скидок
            'measuring' => Calculator::getNeedMeasuring(),
            'measuring_price' => Calculator::getMeasuringPrice(),
            'discounted_measuring_price' => Calculator::getMeasuringPrice(), // todo скидки
            'comment' => request()->input('comment') ?? 'Комментарий отсутствует',
            'products_count' => Calculator::getCount(),
            'installing_difficult' => request()->input('coefficient'),
            'is_private_person' => request()->input('person') == 'physical',
            'structure' => 'Пока не готово',
        ]);
    }

    function newProduct(Order $order) {
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

    function createSalary(Order $order) {
        return InstallerSalary::create([
            'installer_id' => $order->installer_id,
            'category_id' => request()->input('categories'),
            'order_id' => $order->id,
            'sum' => Calculator::getInstallersWage(),
            'comment' => 'Пока не готово',
            'status' => false,
            'changed_sum' => Calculator::getInstallersWage(),
            'created_user_id' => auth()->user()->getAuthIdentifier(),
            'type' => 'Заказ', // todo сделать Enum для этого
        ]);
    }

    function salary(ProductInOrder $productInOrder) {
        $salary = $productInOrder->order
            ->salaries()
            ->where('category_id', $productInOrder->category_id);
        if (!$salary->exists()) {
            return $productInOrder->order
                ->salaries()
                ->first();
        }

        return $salary;
    }

    function updateSalary(int|float $sum, ProductInOrder $productInOrder) {
        $salary = salary($productInOrder)
            ->first();

        $salary->sum = $sum;
        $salary->update();
    }

    function warning(string $text) {
        session()->push('warnings', $text);
    }

    function checkSalaryForMeasuringAndDelivery(Order $order, ProductInOrder $productInOrder) {
        if (orderHasInstallation($order) || Calculator::productNeedInstallation()) {
            $order->measuring_price = 0;
        } else {
            $order->measuring_price = SystemVariables::value('measuring');
            // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
            updateSalary(Calculator::getInstallersWage(), $productInOrder);
        }
    }

    function addProductToOrder(Order $order) {
        $newProductPrice = Calculator::getPrice();

        if ($order->measuring_price) {
            $newProductPrice -= Calculator::getMeasuringPrice();
            if (Calculator::productNeedInstallation()) {
                $order->price -= $order->measuring_price;
                $order->measuring_price = 0;
            }
        }

        if ($order->delivery) {
            $newProductPrice -= min(
                $order->delivery,
                Calculator::getDeliveryPrice()
            );

            $order->delivery = max(
                Calculator::getDeliveryPrice(),
                $order->delivery
            );
        }

        $order->price += $newProductPrice;
        $order->products_count += Calculator::getCount();

        $order->update();

        $product = newProduct($order->refresh());

        updateOrCreateSalary($product);

        return $product;
    }

    function orderHasInstallation(Order $order): bool {
        return $order->products->contains(function ($product) {
            return productHasInstallation($product);
        });
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
            return session('oldProduct');
        }

        return session('oldProduct')->$field;
    }

    function productHasInstallation(ProductInOrder $productInOrder) {
        return
            isset($productInOrder->installation_id) &&
            $productInOrder->installation_id &&
            $productInOrder->installation_id != 14;
    }

    function oldProductHasInstallation(): bool {
        return productHasInstallation(oldProduct());
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
