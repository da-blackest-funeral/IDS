<?php

    // todo возможно вообще сделать фасады для всех своих хелперов
    // чтобы определить четкий интерфейс для их использования
    // тем более что они не реюзабельны, можно сделать фасад для москитных систем, для стеклопакетов и т.д.
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Interfaces\Calculator;

    require_once 'MosquitoSystems.php';

    function isOrderPage() {
        return Route::is('new-order', 'order');
    }

    function notify($text) {
        session()->flash('notifications', [$text]);
    }

    function createOrder(Calculator $calculator) {
        return Order::create([
            'delivery' => $calculator->getDeliveryPrice(),
            'user_id' => auth()->user()->getAuthIdentifier(),
            'installer_id' => request()->input('installer') ?? 2,
            'price' => $calculator->getPrice(),
            'discounted_price' => $calculator->getPrice(), // todo сделать расчет с учетом скидок
            'measuring' => $calculator->getNeedMeasuring(),
            'measuring_price' => $calculator->getMeasuringPrice(),
            'discounted_measuring_price' => $calculator->getMeasuringPrice(), // todo скидки
            'comment' => request()->input('comment') ?? 'Комментарий отсутствует',
            'products_count' => $calculator->getCount(),
            'installing_difficult' => request()->input('coefficient'),
            'is_private_person' => request()->input('person') == 'physical',
            'structure' => 'Пока не готово',
        ]);
    }

    function newProduct(Calculator $calculator, Order $order) {
        return ProductInOrder::create([
            'installation_id' => $calculator->getInstallation('additional_id') ?? 0,
            'order_id' => $order->id,
            'name' => $calculator->getProduct()->name(),
            'data' => $calculator->getOptions()->toJson(),
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

    function createSalary(Order $order, Calculator $calculator) {
        return InstallerSalary::create([
            'installer_id' => $order->installer_id,
            'category_id' => \request()->input('categories'),
            'order_id' => $order->id,
            'sum' => $calculator->getInstallersWage(),
            'comment' => 'Пока не готово',
            'status' => false,
            'changed_sum' => $calculator->getInstallersWage(),
            'created_user_id' => auth()->user()->getAuthIdentifier(),
            'type' => 'Заказ', // todo сделать Enum для этого
        ]);
    }

    function salary(ProductInOrder $productInOrder) {
        return $productInOrder->order
            ->salaries()
            ->whereCategoryId($productInOrder->category_id);
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
