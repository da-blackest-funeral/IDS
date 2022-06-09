<?php

    namespace App\Http\Controllers;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\CreateSalaryDto;
    use App\Services\Helpers\Classes\CreateSalaryService;
    use App\Services\Helpers\Classes\SalaryHelper;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use App\Services\Visitors\Classes\UpdateOrderCommandComposite;
    use App\Services\Visitors\Classes\UpdateOrderDto;
    use App\Services\Visitors\Interfaces\CommandComposite;
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

            \OrderHelper::addProduct();

            if (\OrderHelper::orderOrProductHasInstallation()) {
                \SalaryHelper::checkMeasuringAndDelivery();
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
            $salary = \SalaryHelper::salariesNoInstallation()
                ->first();

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
                $createSalaryDto->setComment('Пока не готово');
                $createSalaryDto->setStatus(0);
                $createSalaryDto->setChangedSum(0);
                $createSalaryDto->setUserId(auth()->user()->getAuthIdentifier());
                $createSalaryDto->setType(SalaryTypesEnum::NO_INSTALLATION->value);

                \SalaryHelper::make($createSalaryDto);
            }

            $data = request()->only([
                'delivery',
                'measuring',
                'count-additional-visits',
                'kilometres',
            ]);
            $data['measuring-price'] = (int)systemVariable('measuring');

            $dto = new UpdateOrderDto($data);

            /** @var CommandComposite $composite */
            $composite = new UpdateOrderCommandComposite(
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
                'error' => 'Не удалось обновить заказ',
            ]);
        }
    }
