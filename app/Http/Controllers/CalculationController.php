<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\User;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\OrderHelper;
    use Illuminate\Http\Request;

    class CalculationController extends Controller
    {
        public function index() {
            return view('welcome')
                ->with(dataForOrderPage());
        }

        // todo удалить этот контроллер вообще и перенести в orderscontroller
        public function save(Calculator $calculator) {
            $calculator->calculate();
            $calculator->saveInfo();

            $order = \OrderHelper::make();
            \OrderHelper::use($order);

            \SalaryHelper::create();
            \ProductHelper::make();

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect(route('order', ['order' => $order->id]));
        }
    }
