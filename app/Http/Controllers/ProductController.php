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

            \OrderHelper::remove($productInOrder);

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
             * todo баг
             * ситуация:
             * 1) в заказе есть рамная москитная сетка
             * 2) есть москитная дверь
             * при удалении москитной двери нужно чтобы доставка становилась 600 и цена самого заказа возвращалась
             * todo по поводу доставки ставить в $order->delivery значение максимальной цены доставки во всем заказе
             */

            /*
             * При удалении товара
             * 1) проверить доставку и монтаж
             * 2) удалить\обновить зарплату за него
             */

            /*
             * 1) если в заказе есть товары данного типа, то посчитать за них зарплату с помощью updateOrCreateSalary
             * 2) если таких товаров нет, то просто удалить зарплату
             * 3) если кроме удаленного товара в заказе нет товаров с монтажом, то замер сделать не бесплатным,
             * зарплаты с типом NO_INSTALLATION вернуть
             */

            \OrderHelper::remove($productInOrder);
            \OrderHelper::calculateMeasuringOptions();
            \OrderHelper::calculateDeliveryOptions();

            $sameCategoryProducts = ProductRepository::byCategoryWithout($productInOrder);

            if ($sameCategoryProducts->isNotEmpty()) {
                \ProductHelper::use(
                    $sameCategoryProducts->first()
                )->updateOrCreateSalary();
            } else {
                \SalaryHelper::salary()->delete();
            }

            $productInOrder->delete();
            $order->update();

            return redirect(route('order', ['order' => $order->id]));
        }
    }
