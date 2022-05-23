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
    use Tests\CreatesApplication;
    use Tests\TestCase;

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
                $this->order->price == $price + $visits * $this->order->delivery
                + $kilometres * systemVariable('additionalPriceDeliveryPerKm')
            );
        }
    }
