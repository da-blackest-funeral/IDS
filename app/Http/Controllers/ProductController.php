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
            session()->put('oldProduct', $productInOrder);

            foreach ($productData->additional as $additional) {
                $order->price -= $additional->price;
            }
            // todo видимо не отнимаются цены за доставку и замер от общей цены заказа
            // todo при обновлении товара если выставить ему коэффициент сложности то сумма заказа неправильно считается

            $order->update();

            addProductToOrder(
                calculator: $calculator,
                order: $order->refresh()
            );

            checkSalaryForMeasuringAndDelivery(
                order: $order,
                calculator: $calculator,
                productInOrder: $productInOrder
            );

            $productInOrder->delete();
            $order->update();

            // при обновлении уже существующего товара нужно
            // 1) отнять стоимость старого товара
            // 2) если был монтаж, а стал без монтажа, то замер надо сделать снова не бесплатным
            // 3) отнять количество товара от общего количества всех товаров в заказе
            return redirect(route('order', ['order' => $order->id]));

        }
    }
