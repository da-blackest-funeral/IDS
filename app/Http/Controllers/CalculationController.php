<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\User;
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
                'installers' => User::role('installer')->get()
            ]);
        }

        public function save(Calculator $calculator, SaveOrderRequest $request) {
            $this->request = $request;
            $this->calculator = $calculator;

            $calculator->calculate();
            // todo сделать логику с "была ли взята машина компании"
            // todo сделать в калькуляторе логику просчета общей стоимости заказа
            // todo соответствующее поле в таблице order
            // todo сделать учет ручного изменения цены заказа
            // todo сделать вывод всевозможных сообщений
//            dump([
//                'price all order' => $this->calculator->getPrice(),
//                'options' => $this->calculator->getOptions()
//            ]);
            $order = $this->createOrder();

            $this->createProductInOrder($order->id);

            $this->createSalary($order);

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect("/orders/$order->id");
        }

        protected function createOrder() {
            // todo по дефолту сделать что замер нужен, монтаж нужен, доставка нужна
            return Order::create([
                'user_id' => auth()->user()->getAuthIdentifier(),
                'installer_id' => $this->request->get('installer') ?? 2,
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
                'name' => $this->calculator->getProduct()->name(),
                'count' => $this->request->get('count'),
                'data' => $this->calculator->getOptions()->toJson(),
            ]);
        }

        protected function createSalary(Order $order) {
            return InstallerSalary::create([
                'installer_id' => $order->installer_id,
                'order_id' => $order->id,
                'sum' => $this->calculator->getInstallersWage(),
                // todo вытаскивать сумму из калькулятора installers wage, при добавлении товаров увеличивать её
                'comment' => 'Пока не готово',
                'status' => false,
                'changed_sum' => $this->calculator->getInstallersWage(),
                'created_user_id' => auth()->user()->getAuthIdentifier(),
                'type' => 'Заказ', // todo сделать Enum для этого
            ]);
        }
    }
