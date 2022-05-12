<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Visitors\Classes\UpdateOrderVisitor;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {
        protected Request $request;

        public function index() {
            return view('pages.orders.all')
                ->with([
                    'orders' => Order::orderByDesc('created_at')
                        ->paginate(3)
                ]);
        }

        public function show(Order $order) {
            return view('welcome')->with(
                \Arr::add(dataForOrderPage(), 'products', $order->products)
            );
        }

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
            $order->products->each(function ($product) {
               $product->delete();
            });

            $order->salaries->each(function ($salary) {
               $salary->delete();
            });

            $order->delete();

            return redirect(route('all-orders'));
        }

        public function update(Order $order, UpdateOrderVisitor $visitor) {
            /*
             * Что надо обновлять
             * 1) нужна ли доставка
             */

            // если доставка изменилась с "нужна" на "не нужна", то уменьшать зарплату
            // если наоборот - увеличивать

            $visitor->execute();
            return redirect(route('order', ['order' => $order->id]));
        }
    }
