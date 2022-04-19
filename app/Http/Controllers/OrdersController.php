<?php

    namespace App\Http\Controllers;

    use App\Models\Order;
    use App\Services\Helpers\OrderHelper;
    use App\Services\Helpers\SalaryHelper;

    class OrdersController extends Controller
    {

        // returns all orders
        public function index() {
            //
        }

        public function show(Order $order) {
            return view('welcome')->with(
                orderData($order)
            );
        }

        public function addProduct(Order $order) {
            $productInOrder = OrderHelper::addProduct(order: $order);

            SalaryHelper::measuringAndDelivery(
                order: $order,
                productInOrder: $productInOrder
            );

            return redirect(route('order', ['order' => $order->id]));
        }

        // todo функция которая обновляет общие данные о заказе
    }
