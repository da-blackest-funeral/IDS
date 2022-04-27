<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class ProductController extends Controller
    {

        public function __construct(SaveOrderRequest $request) {
        }

        public function index(Order $order, ProductInOrder $productInOrder) {
            return view('pages.add-product')->with([
                // todo часть данных отсюда общая и ее можно вынести в отдельный метод
                'data' => Category::all(),
                'superCategories' => Category::whereIn(
                    'id', Category::select(['parent_id'])
                    ->whereNotNull('parent_id')
                    ->groupBy(['parent_id'])
                    ->get()
                    ->toArray()
                )->get(),
                'orderNumber' => $order->id,
                'product' => $productInOrder,
                'productData' => json_decode($productInOrder->data),
                'needPreload' => true,
            ]);
        }

        public function update(Order $order, ProductInOrder $productInOrder) {

            // todo баги
            // разные баги с зарплатой возникают когда меняешь монтаж у товара с одного на другой
            // думаю дело в старом товаре который еще не удален

            $productData = json_decode($productInOrder->data);
            $order->price -= $productData->main_price;
            $order->products_count -= $productInOrder->count;
            session()->put('oldProduct', $productInOrder);

            foreach ($productData->additional as $additional) {
                $order->price -= $additional->price;
            }

            $order->update();

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery(
                    order: $productInOrder->order,
                    productInOrder: $productInOrder
                );
            }

            \OrderHelper::addProduct();

            $productInOrder->delete();
            $order->update();

            // при обновлении уже существующего товара нужно
            // 1) отнять стоимость старого товара
            // 2) если был монтаж, а стал без монтажа, то замер надо сделать снова не бесплатным
            // 3) отнять количество товара от общего количества всех товаров в заказе
            return redirect(route('order', ['order' => $order->id]));
        }
    }
