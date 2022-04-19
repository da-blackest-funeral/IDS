<?php

    namespace App\Http\Controllers;

    use App\Services\Helpers\OrderHelper;
    use App\Services\Helpers\ProductHelper;
    use App\Services\Helpers\SalaryHelper;

    class CalculationController extends Controller
    {

        public function index() {
            return view('welcome')
                ->with(newOrderData());
        }

        public function save() {
            $order = OrderHelper::make();

            SalaryHelper::make($order);

            ProductHelper::make($order);

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect(route('order', ['order' => $order->id]));
        }
    }
