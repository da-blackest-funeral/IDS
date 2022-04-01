<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {

        protected Calculator $calculator;
        protected Request $request;

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

        public function addProduct(Calculator $calculator, Request $request, Order $order) {
            $this->request = $request;
            $this->calculator = $calculator;

            $this->calculator->calculate();
            $newProductPrice = $this->calculator->getPrice();

            if ($order->measuring) {
                $newProductPrice -= $this->calculator->getMeasuringPrice();
            }

            if ($order->delivery) {
                $newProductPrice -= $this->calculator->getDeliveryPrice();
            }

            $order->price += $newProductPrice;
            $order->products_count += $calculator->getCount();

            $order->update();

            createProductInOrder($order->refresh(), $calculator);

//            updateSalary($calculator->getInstallersWage(), $order);

            return redirect("/orders/$order->id");

            // todo увеличение зарплаты при обновлении заказа
            // todo считаем данные по нынешнему товару с помощью калькулятора
            // todo если в заказе уже заданы доставка \ замер, то вычитаем из цены цену доставки \ замера
        }
    }
