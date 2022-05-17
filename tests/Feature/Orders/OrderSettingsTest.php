<?php

    namespace Tests\Feature\Orders;

    use App\Models\Order;
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
                'sum' => $this->testHelper->salaryNoInstallation() - systemVariable('delivery')
            ]);
        }
    }
