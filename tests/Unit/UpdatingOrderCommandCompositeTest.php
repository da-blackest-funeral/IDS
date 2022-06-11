<?php

    namespace Tests\Unit;

    use App\Models\Order;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Commands\Classes\DeliveryKilometresCommand;
    use App\Services\Commands\Classes\RemoveDeliveryCommand;
    use App\Services\Commands\Classes\SetAdditionalVisitsCommand;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use App\Services\Repositories\Classes\UpdateOrderCommandRepository;
    use App\Services\Repositories\Classes\UpdateOrderDto;
    use Illuminate\Foundation\Testing\DatabaseTransactions;
    use Tests\CreatesApplication;
    use Tests\TestCase;

    class UpdatingOrderCommandCompositeTest extends TestCase
    {
        use CreatesApplication, DatabaseTransactions;

        private Order $order;

        private InstallerSalary $salary;

        public function setUpDefaultActions() {
            \Artisan::call('migrate');

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
            $composite = new UpdateOrderCommandRepository($dto, $this->order, $this->salary);
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
            $visits = rand(1, 2);

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

            $this->salary->refresh();
            $this->order->refresh();

            self::assertTrue(
                $this->salary->sum ==
                systemVariable('delivery') * (
                    $visits +
                    $this->order->need_delivery +
                    $this->order->measuring
                ) +
                $kilometres * systemVariable('additionalWagePerKm') *
                ($visits + $this->order->measuring) *
                ($this->order->measuring + $this->order->need_delivery)
            );

            self::assertTrue(
                $this->order->price ==
                $price
                + $visits
                * $this->order->delivery
                + ($kilometres * (int)systemVariable('additionalPriceDeliveryPerKm'))
                * ($this->order->measuring + 1)
                * ($visits + 1)
            );
        }

        /**
         * @return void
         * @test
         */
        public function decrease_delivery_kilometres_command() {
            $this->setUpDefaultActions();
            $firstKilometres = rand(6, 15);
            $defaultOrderPrice = rand(2000, 5000);

            $this->order->price = $defaultOrderPrice +
                $firstKilometres * systemVariable('additionalPriceDeliveryPerKm')
                * ($this->order->measuring + 1);

            $this->salary->sum = 960 +
                systemVariable('additionalWagePerKm') * $firstKilometres
                * ($this->order->measuring + 1);

            $this->order->kilometres = $firstKilometres;
            $kilometres = rand(1, 2);

            (new DeliveryKilometresCommand(
                kilometres: $kilometres,
                order: $this->order,
                salary: $this->salary
            ))->execute()->save();

            $this->salary->refresh();
            $this->order->refresh();

            self::assertEquals(
                $this->salary->sum,
                systemVariable('delivery') +
                systemVariable('measuringWage') +
                $kilometres * systemVariable('additionalWagePerKm') * 2
            );

            self::assertEquals(
                $this->order->price,
                $defaultOrderPrice
                + ($kilometres * (int)systemVariable('additionalPriceDeliveryPerKm'))
                * ($this->order->measuring + 1),
                $this->order->price
            );
        }
    }
