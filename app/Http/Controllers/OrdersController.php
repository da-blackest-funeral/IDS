<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {
        protected Request $request;

        public function index() {
            return view('pages.orders.all')
                ->with([
                    'orders' => Order::orderByDesc('created_at')
                        ->paginate(3),
                ]);
        }

        // todo rename in 'show'
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
            \OrderHelper::addProduct();

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery();
            }

            return redirect(route('order', ['order' => $order->id]));
        }

        public function delete(Order $order) {
            /*
             * 1) удалить все товары связанные с заказом
             * 2) удалить все зарплаты
             * 3) удалить сам заказ
             * 4) отобразить сообщение об успешном удалении
             * 5) вернуть редирект на страницу со всеми заказами
             */

            $order->products->each(function ($product) {
               $product->delete();
            });

            $order->salaries->each(function ($salary) {
               $salary->delete();
            });

            $order->delete();

            return redirect(route('all-orders'));
        }

        // todo функция которая обновляет общие данные о заказе
    }
