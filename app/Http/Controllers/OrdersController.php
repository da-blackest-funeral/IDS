<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {
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

        public function addProduct(Order $order) {
            $productInOrder = \OrderHelper::addProductTo($order);

            \SalaryHelper::checkMeasuringAndDelivery(
                order: $order,
                productInOrder: $productInOrder
            );

            return redirect(route('order', ['order' => $order->id]));
        }

        // todo функция которая обновляет общие данные о заказе
    }
