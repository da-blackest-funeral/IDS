<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;

    class ProductController extends Controller
    {

        public function __construct(SaveOrderRequest $request) {
        }

        public function index(Order $order, ProductInOrder $productInOrder) {
            return view('pages.add-product')->with([
                // todo часть данных отсюда общая и ее можно вынести в отдельный метод
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
            ]);
        }

        public function update(Order $order, ProductInOrder $productInOrder) {

            // todo баги
            // разные баги с зарплатой возникают когда меняешь монтаж у товара с одного на другой
            // думаю дело в старом товаре который еще не удален
            // более того, здесь $productInOrder и является этим старым неудаленным товаром

            $order->price -= $productInOrder->data->main_price;
            $order->products_count -= $productInOrder->count;
            session()->put('oldProduct', $productInOrder);

            foreach ($productInOrder->data->additional as $additional) {
                $order->price -= $additional->price;
            }

            $order->update();

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery();
            }

            \OrderHelper::addProduct();
            $productInOrder->delete();
            $order->update();

            return redirect(route('order', ['order' => $order->id]));
        }
    }
