<?php

    namespace Tests\Unit;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Classes\DeliveryKilometresCommand;
    use App\Services\Commands\Classes\RemoveDeliveryCommand;
    use App\Services\Commands\Classes\SetAdditionalVisitsCommand;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use App\Services\Visitors\Classes\UpdateOrderCommandComposite;
    use App\Services\Visitors\Classes\UpdateOrderDto;
    use App\Services\Visitors\Interfaces\CommandComposite;
    use Tests\CreatesApplication;
    use Tests\TestCase;
    use function PHPUnit\Framework\assertTrue;

    class UpdatingOrderCommandCompositeTest extends TestCase
    {
        use CreatesApplication;

        private Order $order;

        private InstallerSalary $salary;

        public function setUpDefaultActions() {
            \Artisan::call('migrate');
//            \Artisan::call('db:seed');

            $this->order = Order::factory()->create();

            $this->testHelper->createDefaultSalary(type: SalaryTypesEnum::NO_INSTALLATION->value);

            $this->salary = InstallerSalary::first();
        }

        /**
         * @test
         * @return void
         */
        public function remove_delivery_command_test() {
            $this->setUpDefaultActions();

            $price = $this->order->price;
            $salary = $this->salary->sum;
            $visits = $this->order->additional_visits;
            $deliveryPrice = (1 + $visits) * $this->testHelper->defaultDeliverySum();

            $dto = new UpdateOrderDto([
                'delivery' => true,
                'measuring-price' => systemVariable('measuring'),
            ]);
            $composite = new UpdateOrderCommandComposite($dto, $this->order, $this->salary);
            $composite->addCommand(new RemoveDeliveryCommand($this->order, $this->salary))
                ->execute();

            self::assertTrue($composite->result());
            self::assertTrue(
                $this->order->price == $price -= $deliveryPrice
            );

            self::assertTrue(
                $this->salary->sum == $salary - systemVariable('delivery')
            );
        }

        /**
         * @return void
         * @test
         */
        public function delivery_kilometres_command_test() {
            $this->setUpDefaultActions();

            $kilometres = rand(5, 15);
            $price = $this->order->price;
            $salary = $this->salary->sum;
            $visits = rand(1, 2);

            $deliveryPrice = ($visits + 1) * (
                    $this->testHelper->defaultDeliverySum() +
                    systemVariable('additionalPriceDeliveryPerKm')
                    * $kilometres
                );

            $dto = new UpdateOrderDto([
                'delivery' => true,
                'measuring-price' => systemVariable('measuring'),
                'kilometres' => $kilometres,
                'count-additional-visits' => $visits,
                'measuring' => true,
            ]);

            (new SetAdditionalVisitsCommand(
                order: $this->order,
                salary: $this->salary,
                visits: $visits
            ))->execute()->save();

            $this->order->refresh();
            $this->salary->refresh();

            (new DeliveryKilometresCommand(
                kilometres: $kilometres,
                order: $this->order,
                salary: $this->salary
            ))->execute()->save();

//            $this->salary->refresh();
//            $this->order->refresh();
//            (new SetAdditionalVisitsCommand(
//                order: $this->order,
//                salary: $this->salary,
//                visits: $visits
//            ))->execute()

//            $composite->addCommand(
//
//            )
//                ->addCommand(
//                new DeliveryKilometresCommand(
//                    $kilometres,
//                    $this->order,
//                    $this->salary
//                )
            $this->salary->refresh();
            $this->order->refresh();

            dump($this->salary->sum, $kilometres, $visits);

            self::assertTrue(
                $this->salary->sum == (
                    systemVariable('delivery')
                    + $kilometres * systemVariable('additionalWagePerKm')
                ) * ($visits + 1)
            );
        }
    }
