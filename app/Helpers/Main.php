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

    // todo вынести в два метода: createProduct и updateProduct
    // todo функции в этом файле не совсем реюзабельны, лучше сделать это как файл хелперов для москитных систем
    // todo сделать файл хелперов, который будет подключаться в AppServiceProvider, и который будет подключать все
    // остальные файлы хелперов в своей директории (типо точки входа)
    function createProductInOrder(Order $order, MosquitoSystemsCalculator $calculator) {
        $product = ProductInOrder::whereOrderId($order->id)
            // todo баг с тем что при кол-ве больше двух товар идет отдельно может быть в методе getProduct
            ->where('name', $calculator->getProduct()->name())
            ->first();

//        dump(json_decode($calculator->getOptions()));
//
//        dd(json_decode($product->data));

        if (
            $product !== null &&
            json_decode($calculator->getOptions()->toJson()) == json_decode($product->data)
        ) {
            updateProductInOrder($product, $calculator->getOptions()->get('main_price'));
//            dd([
//                'product count before refresh' => $product->count,
//                'request count' => \request()->input('count'),
//                'count in product after refresh' => $product->refresh()->count,
//                'additional' => $calculator->getAdditional()
////                'price' => $calculator->calculateSalary($product->refresh()->count)
//            ]);
            updateSalary($calculator->calculateSalary($product->refresh()->count), $order);
//            $order->salary->sum = ;
            // todo обновление зарплаты
        } else {
            ProductInOrder::create([
                'order_id' => $order->id,
                'name' => $calculator->getProduct()->name(),
                'data' => $calculator->getOptions()->toJson(),
                'user_id' => auth()->user()->getAuthIdentifier(),
                'category_id' => \request()->input('categories'),
                'count' => \request()->input('count'),
            ]);
        }
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
        $order->salary->sum = $sum;
        $order->salary->update();
    }
