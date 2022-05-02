<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;

    class ProductController extends Controller
    {

        public function __construct(SaveOrderRequest $request) {
        }

        public function index(Order $order, ProductInOrder $productInOrder) {
            $data = dataForOrderPage();
            $data['product'] = $productInOrder;

            return view('pages.add-product')
                ->with($data);
        }

        public function update(Order $order, ProductInOrder $productInOrder) {

            $order->price -= $productInOrder->data->main_price;
            $order->products_count -= $productInOrder->count;
            session()->put('oldProduct', $productInOrder);

            foreach ($productInOrder->data->additional as $additional) {
                $order->price -= $additional->price;
            }

            $order->update();

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery();
            }

            \OrderHelper::addProduct();
            $productInOrder->delete();
            $order->update();

            return redirect(route('order', ['order' => $order->id]));
        }
    }
