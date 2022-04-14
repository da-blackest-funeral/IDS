<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\User;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\OrderHelper;
    use App\Services\Helpers\ProductHelper;
    use App\Services\Helpers\SalaryHelper;
    use Illuminate\Http\Request;

    class CalculationController extends Controller
    {

        protected Request $request;
        protected Calculator $calculator;

        public function index() {
            // todo выборку этих данных вынести в отдельный метод т.к. она дублируется
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
                // todo выделить отдельный роут для этого
                'installers' => User::role('installer')->get()
            ]);
        }

        public function save(SaveOrderRequest $request) {
            $this->request = $request;

            // todo сделать логику с "была ли взята машина компании"
            // todo соответствующее поле в таблице order
            // todo сделать учет ручного изменения цены заказа
            // todo сделать вывод всевозможных сообщений
            $order = OrderHelper::make();

            SalaryHelper::make($order);

            // todo тут сделано только для москитных систем (возможно, удастся сделать это реюзабельным)
            ProductHelper::make($order);

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect(route('order', ['order' => $order->id]));
        }
    }
