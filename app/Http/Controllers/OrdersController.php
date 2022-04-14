<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {

        protected Calculator $calculator;
        protected Request $request;

        public function index()
        {
//            return response()->json([
//                'data' => Category::all(),
//                'superCategories' => Category::whereIn(
//                    'id', Category::select(['parent_id'])
//                    ->whereNotNull('parent_id')
//                    ->groupBy(['parent_id'])
//                    ->get()
//                    ->toArray()
//                )->get(),
//                'orderNumber' => Order::count() + 1,
//                'installers' => User::role('installer')->get()
//            ]);
        }

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

        public function addProduct(Order $order) {

            $productInOrder = addProductToOrder(
                order: $order
            );

            checkSalaryForMeasuringAndDelivery(
                order: $order,
                productInOrder: $productInOrder
            );

            return redirect(route('order', ['order' => $order->id]));
        }

        // todo функция которая обновляет общие данные о заказе
    }
