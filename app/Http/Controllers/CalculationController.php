<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Category;
    use App\Models\Order;
    use App\Models\User;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\CreateProductDto;
    use App\Services\Helpers\Classes\OrderService;
    use Illuminate\Http\Request;

    class CalculationController extends Controller
    {
        public function index() {
            return view('welcome')
                ->with(dataForOrderPage());
        }

        // todo удалить этот контроллер вообще и перенести в orderscontroller
        public function save(Calculator $calculator) {
            $calculator->calculate();
            $calculator->saveInfo();

            $dto = new CreateProductDto();
            $dto->setUserId(auth()->user()->getAuthIdentifier())
                ->setComment(request()->input('comment') ?? 'Нет комментария')
                ->setCategoryId(request()->input('categories'))
                ->setCount(request()->input('count', 1))
                // todo убрать из калькулятора функционал installation,
                //  делегировать его отдельному классу
                ->setData($calculator->getOptions())
                ->setInstallationId($calculator->getInstallation('additional_id'))
                ->setName($calculator->getProduct()->name());

            $order = \OrderService::make();
            $dto->setOrderId($order->id);

            \OrderService::use($order);

            \SalaryService::setOrder($order);
            \SalaryService::create();

            \ProductService::make($dto);

            session()->flash('success', ['Заказ успешно создан!']);

            return redirect(route('order', ['order' => $order->id]));
        }
    }
