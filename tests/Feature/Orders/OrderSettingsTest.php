<?php

    namespace Tests\Feature\Orders;

    use App\Services\Helpers\Config\SalaryTypesEnum;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class OrderSettingsTest extends TestCase
    {
        use RefreshDatabase;

        /**
         * @return void
         * @test
         */
        public function set_no_delivery() {
            $this->setUpDefaultActions();
            $this->testHelper->createDefaultOrder(2362);
            $this->testHelper->createDefaultSalary();

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 0,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => 2362 - $this->testHelper->defaultDeliverySum(),
                'delivery' => 0,
            ])->assertNotSoftDeleted('installers_salaries', [
                'sum' => $this->testHelper->salaryNoInstallation() - systemVariable('delivery'),
            ]);
        }

        /**
         * @return void
         * @test
         */
        public function set_no_measuring() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(2362);
            $this->testHelper->createDefaultSalary();
            $this->testHelper->createDefaultProduct();

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'measuring' => 0,
            ]);

            $this->assertNotSoftDeleted('orders', [
                'price' => 1762,
                'delivery' => 600,
                'additional_visits' => 0,
                'measuring' => 0,
                'measuring_price' => 0,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => 480,
                'type' => SalaryTypesEnum::NO_INSTALLATION->value,
            ]);
        }

        /**
         * @return void
         * @test
         */
        public function set_measuring() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(
                price: 1762,
                measuringPrice: 0,
                measuring: false,
            );

            $this->testHelper->createDefaultSalary(480);

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'measuring' => 1,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => 2362,
                'measuring' => true,
                'measuring_price' => 600,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => 960
            ]);
        }
    }
