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
        public function save() {
            // todo сделать логику с "была ли взята машина компании"
            // todo соответствующее поле в таблице order
            // todo сделать учет ручного изменения цены заказа
            $order = \OrderHelper::make();
            \OrderHelper::use($order);

            \SalaryHelper::make();
            \ProductHelper::make();

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect(route('order', ['order' => $order->id]));
        }
    }
