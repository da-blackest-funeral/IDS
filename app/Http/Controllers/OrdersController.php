<?php

    namespace App\Http\Controllers;

    use App\Models\Order;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\CreateSalaryDto;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use App\Services\Repositories\Classes\UpdateOrderCommandRepository;
    use App\Services\Repositories\Classes\UpdateOrderDto;
    use App\Services\Repositories\Interfaces\CommandRepository;
    use Illuminate\Http\Request;

    class OrdersController extends Controller
    {
        protected Request $request;

        public function index() {
            return view('pages.orders.all')
                ->with([
                    'orders' => Order::orderByDesc('id')
                        ->paginate(10, [
                            'orders.created_at',
                            'orders.id',
                            'orders.user_id',
                            'orders.prepayment',
                            'orders.price',
                            'orders.status',
                        ]),
                ]);
        }

        public function show(Order $order) {
            return view('welcome')->with(
                \Arr::add(dataForOrderPage(), 'products', $order->products)
            );
        }

        /**
         * @throws \Throwable
         */
        public function addProduct(Order $order, Calculator $calculator) {
            $calculator->calculate();
            $calculator->saveInfo();

            \OrderService::addProduct();

            if (\OrderService::orderOrProductHasInstallation()) {
                \SalaryService::checkMeasuringAndDelivery();
            }

            return redirect(route('order', ['order' => $order->id]));
        }

        public function delete(Order $order) {
            $order->products()->delete();
            $order->salaries()->delete();
            $order->delete();

            return redirect(route('all-orders'));
        }

        public function update(Order $order) {
            $salary = \SalaryService::salariesNoInstallation()
                ->first();

            \SalaryService::setOrder($order);

            if (is_null($salary)) {
                $createSalaryDto = new CreateSalaryDto();
                $createSalaryDto->setInstallersWage(0);
                $createSalaryDto->setInstallerId($order->installer_id);
                $createSalaryDto->setOrder($order);
                $createSalaryDto->setCategory(
                    $order->products()
                        ->first('category_id')
                        ->category_id
                );
                $createSalaryDto->setComment('???????? ???? ????????????');
                $createSalaryDto->setStatus(0);
                $createSalaryDto->setChangedSum(0);
                $createSalaryDto->setUserId(auth()->user()->getAuthIdentifier());
                $createSalaryDto->setType(SalaryTypesEnum::NO_INSTALLATION->value);

                $salary = \SalaryService::make($createSalaryDto);
            }

            $data = request()->only([
                'delivery',
                'measuring',
                'count-additional-visits',
                'kilometres',
            ]);
            $data['measuring-price'] = (int)systemVariable('measuring');

            $dto = new UpdateOrderDto($data);

            /** @var CommandRepository $composite */
            $composite = new UpdateOrderCommandRepository(
                commandData: $dto,
                order: $order,
                salary: $salary
            );

            $composite->commands()
                ->execute();

            if ($composite->result()) {
                return redirect(route('order', ['order' => $order->id]));
            }

            return back()->withErrors([
                'error' => '???? ?????????????? ???????????????? ??????????',
            ]);
        }
    }
