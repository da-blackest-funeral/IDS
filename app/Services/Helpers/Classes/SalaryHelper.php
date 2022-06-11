<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use App\Services\Helpers\Interfaces\SalaryHelperInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    // todo сделать второй интерфейс - installation salary helper interface, туда добавить методы removeNoInstallation
    // и т.д., а так же другой класс, я думаю с наследованием от этого
    class SalaryHelper implements SalaryHelperInterface
    {
        /**
         * @var CreateSalaryService
         */
        private CreateSalaryService $createSalaryService;

        /**
         * @var Order
         */
        private Order $order;

        /**
         * @var ProductInOrder
         */
        private ProductInOrder $productInOrder;

        /**
         * @param Order $order
         * @return void
         */
        public function setOrder(Order $order) {
            $this->order = $order;
        }

        /**
         * @param ProductInOrder $productInOrder
         * @return void
         */
        public function setProductInOrder(ProductInOrder $productInOrder) {
            $this->productInOrder = $productInOrder;
        }

        public function __construct(
            Order $order = null,
            ProductInOrder $productInOrder = null
        ) {
            $this->createSalaryService = new CreateSalaryService();
            $this->order = $order;
            $this->productInOrder = $productInOrder;
        }

        /**
         * @param int|float $sum
         * @param InstallerSalary|null $salary
         * @return void
         */
        public function update(int|float $sum, InstallerSalary $salary = null) {
            if (is_null($salary)) {
                $salary = $this->salary();
                $salary->type = SalaryType::determine(\ProductHelper::getProduct());
            }

            $salary->sum = $sum;

            $salary->update();
        }

        /**
         * @param CreateSalaryDto $dto
         * @return InstallerSalary
         */
        public function make(CreateSalaryDto $dto): InstallerSalary {
            return $this->createSalaryService->make($dto);
        }

        /**
         * @param float|null $sum
         * @param Order|null $order
         * @return InstallerSalary
         */
        public function create(float $sum = null, Order $order = null) {
            return $this->createSalaryService->create(
                order: $order ?? $this->order,
                sum: $sum ?? Calculator::getInstallersWage()
            );
        }

        /**
         * @return void
         */
        public function removeNoInstallation(): void {
            $this->salariesNoInstallation()
                ->each(function (InstallerSalary $salary) {
                    $this->update(0, $salary);
                });
        }

        /**
         * @return bool|void
         */
        public function removeDelivery(InstallerSalary $salary = null) {
            $deliverySalary = SystemVariables::value('delivery') *
                (\OrderHelper::getOrder()->additional_visits + 1);
            if (!is_null($salary)) {
                return $this->removeSingleDelivery($salary, $deliverySalary);
            }

            $this->salariesNoInstallation()
                ->each(fn($salary) => $this->removeSingleDelivery($salary, $deliverySalary));
        }

        /**
         * @param InstallerSalary $salary
         * @param int $deliverySalary
         * @return bool
         */
        private function removeSingleDelivery(InstallerSalary $salary, int $deliverySalary) {
            $salary->sum -= $deliverySalary;
            return $salary->update();
        }

        public function restoreDelivery() {
            $salary = $this->salariesNoInstallation()
                ->first();

            $salary->update([
                'sum' => $salary->sum + SystemVariables::value('delivery') *
                    (\OrderHelper::getOrder()->additional_visits + 1),
            ]);
        }

        /**
         * @param Order|null $order
         * @return Collection<InstallerSalary>
         */
        public function salariesNoInstallation(Order $order = null): Collection {
            /** @var Order $order */
            $order = $order ?? \OrderHelper::getOrder();
            return $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get();
        }

        /**
         * @param Order|null $order
         * @return void
         */
        public function restoreNoInstallation(Order $order = null) {
            $order = $order ?? \OrderHelper::getOrder();

            $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get()
                ->each(function (InstallerSalary $salary) use ($order) {
                    $this->update($this->noInstallationSalarySum($order), $salary);
                });
        }

        /**
         * @param Order $order
         * @return int|float
         */
        protected function noInstallationSalarySum(Order $order): int|float {
            $result = 0;
            if ($order->measuring_price || $order->measuring) {
                $result += SystemVariables::value('measuringWage');
            }

            if ($order->delivery) {
                $result += SystemVariables::value('delivery');
            }

            return $result;
        }

        /**
         * @param ProductInOrder|null $productInOrder
         * @return object
         */
        public function salary(ProductInOrder $productInOrder = null) {
            $productInOrder = $productInOrder ?? \ProductHelper::getProduct();
            return $productInOrder->order
                ->salaries()
                ->where('category_id', $productInOrder->category_id)
                ->firstOrFail();
        }

        /**
         * @param Order|null $order
         * @return bool
         */
        public function hasSalaryNoInstallation(Order $order = null): bool {
            $order = $order ?? \OrderHelper::getOrder();
            return $order
                ->salaries
                ->contains(function (InstallerSalary $salary) {
                    return $salary->type == SalaryTypesEnum::NO_INSTALLATION->value && $salary->sum > 0;
                });
        }

        public function checkMeasuringAndDelivery() {
            $order = \ProductHelper::getProduct()->order;
            if (\OrderHelper::hasInstallation() || Calculator::productNeedInstallation()) {
                $order->measuring_price = 0;
            } else {
                $order->measuring_price = $order->measuring ?
                    SystemVariables::value('measuring')
                    : 0;
                // Прибавить к зп монтажника стоимости замера и доставки, если они заданы
                if (!$this->hasSalaryNoInstallation()) {
                    $this->update(Calculator::getInstallersWage());
                }
            }
        }
    }
