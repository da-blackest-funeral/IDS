<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Repositories\Classes\MosquitoSystemsProductRepository;

    class ProductController extends Controller
    {
        public function index(Order $order, ProductInOrder $productInOrder) {
            return view('pages.add-product')
                ->with(\Arr::add(dataForOrderPage(), 'product', $productInOrder));
        }

        public function update(Order $order, ProductInOrder $productInOrder, Calculator $calculator) {
            $calculator->calculate();
            $calculator->saveInfo();

            \OrderService::remove($productInOrder);

            if (\OrderService::orderOrProductHasInstallation()) {
                \SalaryService::checkMeasuringAndDelivery();
            }

            $requestData = new \stdClass();
            $requestData->comment = request()->comment ?? 'Отсутствует';
            $requestData->categoryId = request()->categories;
            $requestData->count = request()->count;
            $requestData->userId = auth()->user()->getAuthIdentifier();
            $requestData->orderId = $order->id;

            \OrderService::addProduct($calculator, $requestData);

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

            /*
             * 1) если в заказе есть товары данного типа, то посчитать за них зарплату с помощью updateOrCreateSalary
             * 2) если таких товаров нет, то просто удалить зарплату
             * 3) если кроме удаленного товара в заказе нет товаров с монтажом, то замер сделать не бесплатным,
             * зарплаты с типом NO_INSTALLATION вернуть
             */

            \OrderService::remove($productInOrder);
            \OrderService::calculateMeasuringOptions();
            \OrderService::calculateDeliveryOptions();

            $sameCategoryProducts = MosquitoSystemsProductRepository::byCategoryWithout($productInOrder);

            if ($sameCategoryProducts->isNotEmpty()) {
                \ProductService::use(
                    $sameCategoryProducts->first()
                )->updateOrCreateSalary();
            } else {
                \SalaryService::salary()->delete();
                \ProductService::use($productInOrder)
                    ->checkRestoreNoInstallationSalaries();
            }

            $productInOrder->delete();
            $order->update();

            if (!\OrderService::use($order->refresh())->hasProducts()) {
                $order->update([
                    'price' => 0,
                    'measuring_price' => 0,
                    'delivery' => 0,
                ]);
            }

            return redirect(route('order', ['order' => $order->id]));
        }
    }
