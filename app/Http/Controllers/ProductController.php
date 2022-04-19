<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Helpers\MosquitoSystemsHelper;
    use App\Services\Helpers\OrderHelper;
    use App\Services\Helpers\SalaryHelper;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

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
            // todo вынести в отдельный метод, использовать для удаления товаров
            $productData = json_decode($productInOrder->data);
            $order->price -= $productData->main_price;
            $order->products_count -= $productInOrder->count;
            session()->flash('oldProduct', $productInOrder);

            foreach ($productData->additional as $additional) {
                $order->price -= $additional->price;
            }

            if (
                !OrderHelper::hasInstallation($order) &&
                !Calculator::productNeedInstallation() &&
                MosquitoSystemsHelper::oldProductHasInstallation()
            ) {
                $order->measuring_price = Calculator::getMeasuringPrice();
                $order->price += $order->measuring_price;
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

            // при обновлении уже существующего товара нужно
            // 1) отнять стоимость старого товара
            // 2) если был монтаж, а стал без монтажа, то замер надо сделать снова не бесплатным
            // 3) отнять количество товара от общего количества всех товаров в заказе
            return redirect(route('order', ['order' => $order->id]));
        }
    }
