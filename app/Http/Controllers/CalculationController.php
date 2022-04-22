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

        public function save() {
            // todo сделать логику с "была ли взята машина компании"
            // todo соответствующее поле в таблице order
            // todo сделать учет ручного изменения цены заказа
            // todo сделать вывод всевозможных сообщений
            $order = \OrderHelper::make();

            \SalaryHelper::make($order);

            \ProductHelper::make($order);

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect(route('order', ['order' => $order->id]));
        }
    }
