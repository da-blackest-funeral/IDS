<?php

    namespace Tests\Feature\Orders;

    use App\Models\MosquitoSystems\Product;
    use App\Models\Order;
    use App\Models\ProductInOrder;
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
            $order = Order::first();
            $this->testHelper->createDefaultSalary();

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 0,
                'measuring' => 1,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => 2362 -
                    $this->testHelper->defaultDeliverySum() *
                    ($order->additional_visits + 1),
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
                'delivery' => 1,
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
                'delivery' => 1,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => 2362,
                'measuring' => true,
                'measuring_price' => 600,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => 960,
            ])->assertDatabaseCount('installers_salaries', 1);
        }

        /**
         * @return void
         * @test
         */
        public function set_no_delivery_when_has_additional_visits() {
            $this->setUpDefaultActions();
            $visits = rand(2, 5);
            $deliveryPrice = 600;
            $salary = systemVariable('delivery') * ($visits + 1)
                + systemVariable('measuringWage');
            $resultSalary = systemVariable('measuringWage');

            $this->testHelper->createDefaultOrder(
                price: 1762 + $deliveryPrice * ($visits + 1),
                delivery: $deliveryPrice,
                additionalVisits: $visits,
            );

            $this->testHelper->createDefaultSalary(
                sum: $salary,
                type: SalaryTypesEnum::NO_INSTALLATION->value
            );

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 0,
                'count-additional-visits' => $visits,
                'measuring' => 1,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => 1762,
                'delivery' => 0,
                'need_delivery' => 0,
                'additional_visits' => $visits,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => $resultSalary,
            ]);
        }

        /**
         * @return void
         * @test
         */
        public function set_delivery_when_has_many_products() {
            $this->setUpDefaultActions();
            $price = rand(3000, 5000);
            $delivery = $this->testHelper->defaultDeliverySum(2);

            $this->testHelper->createDefaultProduct();
            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultNoInstallationData(type: 2)),
            ]);

            $this->testHelper->createDefaultOrder(
                price: $price,
                delivery: 0,
                needDelivery: false
            );

            $salary = $this->testHelper->salaryNoInstallation();
            $this->testHelper->createDefaultSalary(
                sum: $salary - systemVariable('delivery')
            );

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 1,
                'measuring' => 1,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => $price + $delivery,
                'need_delivery' => 1,
                'delivery' => $delivery,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => $salary,
            ])->assertDatabaseCount('installers_salaries', 1);
        }

        /**
         * @return void
         * @test
         */
        public function set_no_delivery_when_has_many_products() {
            $this->setUpDefaultActions();
            $price = rand(4000, 5000);
            $delivery = $this->testHelper->defaultDeliverySum(2);
            $salary = $this->testHelper->salaryNoInstallation();

            $this->testHelper->createDefaultProduct();
            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultNoInstallationData(type: 2)),
            ]);

            $this->testHelper->createDefaultOrder(
                price: $price,
                delivery: $delivery,
            );

            $this->testHelper->createDefaultSalary(
                sum: $salary
            );

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 0,
                'measuring' => 1,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => $price - $delivery,
                'need_delivery' => false,
                'delivery' => 0,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => $salary - systemVariable('delivery'),
            ]);
        }

        /**
         * @return void
         * @test
         */
        public function set_delivery_many_products_with_visits() {
            $this->setUpDefaultActions();

            $price = rand(4000, 6000);
            $delivery = $this->testHelper->defaultDeliverySum(2);
            $salary = systemVariable('delivery');
            $visits = rand(1, 2);

            $order = $this->testHelper->createDefaultOrder(
                price: $price,
                delivery: 0,
                needDelivery: false,
                additionalVisits: $visits
            );

            $this->testHelper->createDefaultSalary(
                sum: $salary,
                type: SalaryTypesEnum::NO_INSTALLATION->value
            );

            $this->testHelper->createDefaultProduct();
            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultNoInstallationData(type: 2)),
            ]);

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 1,
                'measuring' => 1,
                'count-additional-visits' => $visits,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => $price + $delivery * (1 + $visits),
                'delivery' => $delivery,
                'additional_visits' => $visits,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => $salary * (1 + $visits) + systemVariable('measuringWage')
            ]);
        }

        /**
         * @return void
         * @test
         */
        public function set_no_delivery_when_has_many_products_with_visits() {
            $this->setUpDefaultActions();

            $visits = rand(1, 2);
            $price = rand(4000, 6000);
            $delivery = $this->testHelper->defaultDeliverySum(2);
            $salary = systemVariable('delivery') * (1 + $visits) + systemVariable('measuringWage');

            $this->testHelper->createDefaultOrder(
                price: $price,
                delivery: $this->testHelper->defaultDeliverySum(2),
                additionalVisits: $visits
            );

            $this->testHelper->createDefaultSalary(
                sum: $salary,
                type: SalaryTypesEnum::NO_INSTALLATION->value
            );

            $this->testHelper->createDefaultProduct();
            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultNoInstallationData(type: 2)),
            ]);

            $this->post(route('order', ['order' => 1]), [
                '_method' => 'put',
                'delivery' => 0,
                'measuring' => 1,
                'count-additional-visits' => $visits,
            ]);

            $this->assertDatabaseHas('orders', [
                'price' => $price - $delivery * (1 + $visits),
                'delivery' => 0,
                'additional_visits' => $visits,
            ])->assertDatabaseHas('installers_salaries', [
                'sum' => systemVariable('measuringWage')
            ])->assertDatabaseCount('installers_salaries', 1);
        }

        // todo тесты с километражом
    }
