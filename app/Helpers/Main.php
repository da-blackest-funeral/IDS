<?php

    // todo возможно вообще сделать фасады для всех своих хелперов чтобы определить четкий интерфейс для их
    // использования
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Classes\MosquitoSystemsCalculator;
    use App\Services\Interfaces\Calculator;

    function isOrderPage() {
        return \request()->path() == '/' || substr_count(request()->path(), 'orders',);
    }

    function notify($text) {
        session()->flash('notifications', [$text]);
    }

    function createOrder(Calculator $calculator) {
        return Order::create([
            'delivery' => $calculator->getDeliveryPrice(),
            'user_id' => auth()->user()->getAuthIdentifier(),
            'installer_id' => \request()->input('installer') ?? 2,
            'price' => $calculator->getPrice(),
            'discounted_price' => $calculator->getPrice(), // todo сделать расчет с учетом скидок
            'measuring' => $calculator->getNeedMeasuring(),
            'measuring_price' => $calculator->getMeasuringPrice(),
            'discounted_measuring_price' => $calculator->getMeasuringPrice(), // todo скидки
            'comment' => \request()->input('comment') ?? 'Комментарий отсутствует',
            'products_count' => $calculator->getCount(),
            'installing_difficult' => \request()->input('coefficient'),
            'is_private_person' => \request()->input('person') == 'physical',
            'structure' => 'Пока не готово',
        ]);
    }

    // todo когда замер обнуляется, сделать warning (компонент с иконкой который) что нужно заключить договор
    // todo вынести в два метода: createProduct и updateProduct
    // todo функции в этом файле не совсем реюзабельны, лучше сделать это как файл хелперов для москитных систем
    // todo сделать файл хелперов, который будет подключаться в AppServiceProvider, и который будет подключать все
    // остальные файлы хелперов в своей директории (типо точки входа)
    function createProductInOrder(Order $order, MosquitoSystemsCalculator $calculator) {
        $products = ProductInOrder::whereOrderId($order->id)
            // todo баг из-за main_salary
            ->where('name', $calculator->getProduct()->name())
            ->get();

        // Если был найден товар полностью идентичный уже добавленному
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                // Если все его добавочные опции идентичны
                if (productAlreadyExists($calculator, $product)) {
                    updateProductInOrder($product, $calculator->getOptions()->get('main_price'));
                    notify('Добавленные товары идентичны. Во избежание ошибок лучше сразу указывайте количество товара в поле "Количество".');
                    $count = $product->refresh()->count;
                } else {
                    newProduct($calculator, $order);
                    $count = $product->count + (int)\request()->input('count');
                }

                // todo не считает потому что не задано для "Без монтажа"
                // тогда если name="Без монтажа" и при этом в заказе уже задан монтаж,
                // то вытаскивать этот монтаж и по его id находить по числу старое кол-во + новое
                updateSalary($calculator->calculateSalaryForCount($count, $product), $order);
            }

        } else {
            newProduct($calculator, $order);
        }
    }

    function newProduct($calculator, $order) {
        ProductInOrder::create([
            'installation_id' => $calculator->getInstallation('additional_id') ?? 0,
            'order_id' => $order->id,
            'name' => $calculator->getProduct()->name(),
            'data' => $calculator->getOptions()->toJson(),
            'user_id' => auth()->user()->getAuthIdentifier(),
            'category_id' => \request()->input('categories'),
            'count' => \request()->input('count'),
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
        $product->count += (int)\request()->input('count');
        $data = json_decode($product->data);
        $data->main_price += $mainPrice;
        $product->data = json_encode($data);
        $product->update();
    }

    function createSalary(Order $order, Calculator $calculator) {
        return InstallerSalary::create([
            'installer_id' => $order->installer_id,
            'order_id' => $order->id,
            'sum' => $calculator->getInstallersWage(),
            'comment' => 'Пока не готово',
            'status' => false,
            'changed_sum' => $calculator->getInstallersWage(),
            'created_user_id' => auth()->user()->getAuthIdentifier(),
            'type' => 'Заказ', // todo сделать Enum для этого
        ]);
    }

    function updateSalary(int|float $sum, Order $order) {
        $salary = $order->salary()->first();
        $salary->sum = $sum;
        $salary->save();
    }
