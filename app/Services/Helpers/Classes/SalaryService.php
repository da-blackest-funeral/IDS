<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Interfaces\SalaryServiceInterface;
    use Facades\App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    class SalaryService implements SalaryServiceInterface
    {
        /**
         * @var CreateSalaryService
         */
        private CreateSalaryService $createSalaryService;

        /**
         * @var NoInstallationSalaryService
         */
        private NoInstallationSalaryService $noInstallationService;

        /**
         * @param Order $order
         * @param ProductInOrder $productInOrder
         */
        public function __construct(
            private Order $order,
            private ProductInOrder $productInOrder
        ) {
            $this->createSalaryService = new CreateSalaryService();
            $this->noInstallationService = new NoInstallationSalaryService($order);
        }

        /**
         * @param Order $order
         */
        public function setOrder(Order $order): void {
            $this->order = $order;
        }

        /**
         * @param int|float $sum
         * @param InstallerSalary|null $salary
         * @return void
         */
        public function update(int|float $sum, InstallerSalary $salary = null) {
            if (is_null($salary)) {
                $salary = $this->salary();
                $salary->type = SalaryType::determine(\ProductService::getProduct());
            }

            // todo вернуть этот метод сюда
            $this->noInstallationService->update($sum, $salary);
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
         * @todo передавать сюда $installersWage как параметр или $calculator
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
            $this->noInstallationService->removeAll();
        }

        /**
         * @return bool|void
         */
        public function removeDelivery(InstallerSalary $salary = null) {
            $deliverySalary = SystemVariables::value('delivery') *
                ($this->order->additional_visits + 1);
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

        /**
         * @return void
         */
        public function restoreDelivery() {
            $salary = $this->salariesNoInstallation()
                ->first();

            $salary->update([
                'sum' => $salary->sum + SystemVariables::value('delivery') *
                    ($this->order->additional_visits + 1),
            ]);
        }

        /**
         * @param Order|null $order
         * @return Collection<InstallerSalary>
         */
        public function salariesNoInstallation(Order $order = null): Collection {
            $order = $order ?? $this->order;

            return $this->noInstallationService
                ->salaries($order);
        }

        /**
         * @param Order|null $order
         * @return void
         */
        public function restoreNoInstallation(Order $order = null) {
            $order = $order ?? \OrderService::getOrder();

            $this->noInstallationService
                ->restore($order);
        }

        /**
         * @param Order $order
         * @return int|float
         */
        protected function noInstallationSalarySum(Order $order): int|float {
            return $this->noInstallationService
                ->sum($order);
        }

        /**
         * @param ProductInOrder|null $productInOrder
         * @return object
         */
        public function salary(ProductInOrder $productInOrder = null) {
            $productInOrder = $productInOrder ?? \ProductService::getProduct();

            return $this->noInstallationService
                ->salary($productInOrder);
        }

        /**
         * @param Order|null $order
         * @return bool
         */
        public function hasSalaryNoInstallation(Order $order = null): bool {
            $order = $order ?? \OrderService::getOrder();

            return $this->noInstallationService
                ->hasSalary($order);
        }

        /**
         * @return void
         */
        public function checkMeasuringAndDelivery() {
            // todo убрать колхоз
            $order = \ProductService::getProduct()->order;
            if (\OrderService::hasInstallation() || Calculator::productNeedInstallation()) {
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
