<?php

    namespace App\Services\Helpers\Classes;

    use App\Models\Order;
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
         * @param int|float $sum
         * @param InstallerSalary|null $salary
         * @return void
         */
        public function update(int|float $sum, InstallerSalary $salary = null) {
            if (is_null($salary)) {
                $salary = $this->salary()
                    ->first();
                $salary->type = SalaryType::determine(\ProductHelper::getProduct());
            }

            $salary->sum = $sum;

            $salary->update();
        }

        /**
         * @param bool $condition
         * @param int|float $sum
         * @return void
         */
        public function updateIf(bool $condition, int|float $sum) {
            if ($condition) {
                $this->update($sum);
            }
        }

        /**
         * @param CreateSalaryDto|null $dto
         * @param float|null $sum
         * @param Order|null $order
         * @return InstallerSalary
         */
        public function make(
            CreateSalaryDto $dto = null,
            float $sum = null,
            Order $order = null
        ): InstallerSalary {
            $salaryService = new CreateSalaryService();
            if (!is_null($dto)) {
                return $salaryService->make($dto);
            }

            $dto = new CreateSalaryDto();
            $dto->setCategory(request()->input('categories', 5));
            $dto->setComment('Пока не готово');
            $dto->setInstallersWage($sum ?? Calculator::getInstallersWage());
            $dto->setStatus(false);
            $dto->setChangedSum($dto->getInstallersWage());
            $dto->setOrder($order ?? \OrderHelper::getOrder());
            $dto->setType(SalaryType::determine());
            $dto->setUserId(auth()->user()->getAuthIdentifier());

            return $salaryService->make($dto);
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
            return $order
                ->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get();
        }

        public function restoreNoInstallation() {
            $order = \ProductHelper::getProduct()->order;

            $order->salaries()
                ->where('type', SalaryTypesEnum::NO_INSTALLATION->value)
                ->get()
                ->each(function (InstallerSalary $salary) use ($order) {
                    $this->update($this->noInstallationSalarySum($order), $salary);
                });
        }

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

        public function salary() {
            $productInOrder = \ProductHelper::getProduct();
            $salary = $productInOrder->order
                ->salaries()
                ->where('category_id', $productInOrder->category_id);
            if (!$salary->exists()) {
                return $productInOrder->order
                    ->salaries()
                    ->first();
            }

            return $salary;
        }

        /**
         * @return bool
         */
        public function hasSalaryNoInstallation(): bool {
            return \OrderHelper::getOrder()
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
                $this->updateIf(!$this->hasSalaryNoInstallation(), Calculator::getInstallersWage());
            }
        }
    }
