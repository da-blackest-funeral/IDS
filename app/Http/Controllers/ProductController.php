<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Repositories\Classes\ProductRepository;

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

            \OrderHelper::removeFromOrder($productInOrder);

            session()->put('oldProduct', $productInOrder);

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery();
            }

            \OrderHelper::addProduct();
            $productInOrder->delete();
            $order->update();

            return redirect(route('order', ['order' => $order->id]));
        }

        public function delete(Order $order, ProductInOrder $productInOrder) {
            /*
             * При удалении товара
             * 1) проверить доставку и монтаж
             * 2) удалить\обновить зарплату за него
             */
            \OrderHelper::removeFromOrder($productInOrder);

            \ProductHelper::use(
                ProductRepository::byCategory($productInOrder)
                ->get()
                ->last()
            )->updateOrCreateSalary();

            $productInOrder->delete();
            return redirect(route('order', ['order' => $order->id]));
        }
    }
