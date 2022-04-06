<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {

        protected Calculator $calculator;
        protected Request $request;

        public function order(Order $order) {
            $products = $order->products()->get();
            $data = Category::all();
            $superCategories = Category::whereIn(
                'id', Category::select(['parent_id'])
                ->whereNotNull('parent_id')
                ->groupBy(['parent_id'])
                ->get()
                ->toArray()
            )->get();
            $orderNumber = $order->id;

            return view('welcome')->with(
                compact('data', 'order', 'products', 'superCategories', 'orderNumber')
            );
        }

        public function addProduct(Calculator $calculator, Order $order) {
            addProductToOrder($calculator, $order);

            return redirect(route('order', ['order' => $order->id]));
        }

        // todo функция которая обновляет общие данные о заказе
    }
