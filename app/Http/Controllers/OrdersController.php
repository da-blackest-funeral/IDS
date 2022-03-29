<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {
        public function order(Request $request, $id) {
            $order = Order::whereId($id)->firstOrFail();
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
    }
