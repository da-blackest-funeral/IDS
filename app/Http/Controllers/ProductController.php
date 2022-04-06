<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Interfaces\Calculator;

    class ProductController extends Controller
    {
        protected SaveOrderRequest $request;

        public function __construct(SaveOrderRequest $request) {
            $this->request = $request;
        }

        public function index(Order $order, ProductInOrder $productInOrder) {
            return view('pages.add-product')->with([
                'data' => Category::all(),
                'superCategories' => Category::whereIn(
                    'id', Category::select(['parent_id'])
                    ->whereNotNull('parent_id')
                    ->groupBy(['parent_id'])
                    ->get()
                    ->toArray()
                )->get(),
                'orderNumber' => $order->id,
                'product' => $productInOrder,
                'productData' => json_decode($productInOrder->data),
                'needPreload' => true,
            ]);
        }

        public function update(Order $order, ProductInOrder $productInOrder, Calculator $calculator) {
            $productData = json_decode($productInOrder->data);
            $order->price -= $productData->main_price;
            $order->products_count -= $productInOrder->count;

//            $calculator->calculate();

            foreach ($productData->additional as $additional) {
                $order->price -= $additional->price;
//                dump($additional->price);
            }

            $order->update();

            // todo если был нужен монтаж, и замер был бесплатным, то если не нужен монтаж и нужен замер, то его надо
            // сделать не бесплатным
//            dd($calculator->isNeedInstallation());

            addProductToOrder($calculator, $order->refresh());

            // нельзя проверять только по калькулятору, надо проходить по всем товарам и смотреть, есть ли там монтаж
            // и если найден хоть один товар с монтажом, то
//            dd(hasInstallationInOrder($order), $calculator->isNeedInstallation());
            if (hasInstallationInOrder($order) || $calculator->isNeedInstallation()) {
                $order->measuring_price = 0;
            } else {
                $order->measuring_price = 600;
            }

            $productInOrder->delete();
            $order->update();

            // todo при обновлении уже существующего товара нужно
            // 1) отнять стоимость старого товара
            // 2) если был монтаж, а стал без монтажа, то замер надо сделать снова не бесплатным
            // 3) отнять количество товара от общего количества всех товаров в заказе
            return redirect(route('order', ['order' => $order->id]));

        }
    }
