<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\User;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Http\Request;

    class CalculationController extends Controller
    {

        protected Request $request;
        protected Calculator $calculator;

        public function index() {
            return view('welcome')->with([
                'data' => Category::all(),
                'superCategories' => Category::whereIn(
                    'id', Category::select(['parent_id'])
                    ->whereNotNull('parent_id')
                    ->groupBy(['parent_id'])
                    ->get()
                    ->toArray()
                )->get(),
                'orderNumber' => Order::count() + 1,
                'installers' => User::role('installer')->get()
            ]);
        }

        public function save(Calculator $calculator, SaveOrderRequest $request) {
            $this->request = $request;
            $this->calculator = $calculator;

            $calculator->calculate();
            // todo сделать логику с "была ли взята машина компании"
            // todo соответствующее поле в таблице order
            // todo сделать учет ручного изменения цены заказа
            // todo сделать вывод всевозможных сообщений
            $order = createOrder($calculator);

            createSalary($order, $calculator);

            createProductInOrder($order, $calculator);

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect("/orders/$order->id");
        }
    }
