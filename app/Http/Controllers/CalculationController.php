<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Http\Request;

    class CalculationController extends Controller
    {

        protected Request $request;
        protected Calculator $calculator;

        public function index(Request $request) {
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
            ]);
        }

        public function save(Calculator $calculator, SaveOrderRequest $request) {
            $this->request = $request;
            $this->calculator = $calculator;

            $calculator->calculate();
            // todo сделать логику с "была ли взята машина компании"
            $order = $this->createOrder();

            $product = $this->createProductInOrder($order->id);

            session()->flash('success', ['Заказ успешно создан!', 'Товар успешно добавлен!']);

            return view('welcome')->with([
                'orderNumber' => $order->id,
                'data' => Category::all(),
                'superCategories' => Category::whereIn(
                    'id', Category::select(['parent_id'])
                    ->whereNotNull('parent_id')
                    ->groupBy(['parent_id'])
                    ->get()
                    ->toArray()
                )->get(),
                'productData' => $product,
            ]);
        }

        protected function createOrder() {
            return Order::create([
                'user_id' => auth()->user()->getAuthIdentifier(),
                'price' => $this->calculator->getPrice(),
                'discounted_price' => $this->calculator->getPrice(), // todo сделать расчет с учетом скидок
                'measuring' => $this->calculator->getNeedMeasuring(),
                'measuring_price' => $this->calculator->getMeasuringPrice(),
                'discounted_measuring_price' => $this->calculator->getMeasuringPrice(), // todo скидки
                'comment' => $this->request->get('comment') ?? 'Комментарий отсутствует',
                'products_count' => $this->calculator->getCount(),
                'installing_difficult' => $this->request->get('coefficient'),
                'is_private_person' => $this->request->get('person') == 'physical',
                'structure' => 'Пока не готово',
            ]);
        }

        protected function createProductInOrder(int $orderId) {
            return ProductInOrder::create([
                'order_id' => $orderId,
                'user_id' => auth()->user()->getAuthIdentifier(),
                'category_id' => $this->request->get('categories'),
                'name' => 'Пока не готово',
                'count' => $this->request->get('count'),
                'data' => $this->calculator->getOptions()->toJson(),
            ]);
        }
    }
