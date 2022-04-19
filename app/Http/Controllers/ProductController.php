<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\OrderHelper;
    use App\Services\Helpers\SalaryHelper;

    class ProductController extends Controller
    {
        public function __construct(protected SaveOrderRequest $request) {
        }

        public function index(Order $order, ProductInOrder $productInOrder) {
            $data = orderData($order);
            $data['product'] = $productInOrder;
            $data['productData'] = json_decode($productInOrder->data);
            $data['needPreload'] = true;

            return view('pages.add-product')->with($data);
        }

        public function update(Order $order, ProductInOrder $productInOrder) {
            session()->flash('oldProduct', $productInOrder);

            OrderHelper::reducePrice($order, $productInOrder);

            if (OrderHelper::needAddMeasuring($order)) {
                OrderHelper::addMeasuringPrice($order);
            }

            $order->update();

            OrderHelper::addProduct(
                order: $order->refresh()
            );

            SalaryHelper::measuringAndDelivery(
                order: $order,
                productInOrder: $productInOrder
            );

            $productInOrder->delete();
            $order->update();

            return redirect(route('order', ['order' => $order->id]));
        }
    }
