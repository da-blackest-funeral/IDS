<?php

    namespace App\Http\Controllers;

    use App\Models\Order;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Visitors\Classes\UpdateOrderVisitor;
    use App\Services\Visitors\Interfaces\Visitor;
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

        public function show(Order $order) {
            return view('welcome')->with(
                \Arr::add(dataForOrderPage(), 'products', $order->products)
            );
        }

        /**
         * @throws \Throwable
         */
        public function addProduct(Order $order, Calculator $calculator) {
            $calculator->calculate();
            $calculator->saveInfo();

            \OrderHelper::addProduct();

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery();
            }

            return redirect(route('order', ['order' => $order->id]));
        }

        public function delete(Order $order) {
            $order->products()->delete();
            $order->salaries()->delete();
            $order->delete();

            return redirect(route('all-orders'));
        }

        public function update(Order $order) {
            /** @var Visitor $visitor */
            $visitor = new UpdateOrderVisitor(
                \request()->except(['_method', '_token', 'add',])
            );

            $visitor->execute()->final();

            return redirect(route('order', ['order' => $order->id]));
        }
    }
