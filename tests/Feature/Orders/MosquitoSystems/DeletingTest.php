<?php

    namespace Orders\MosquitoSystems;

    use App\Models\ProductInOrder;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class DeletingTest extends TestCase
    {
        use RefreshDatabase;

        /*
         * Какие тесты нужно написать
         * 1) удаление единственного товара - готово
         * 2) удаление товара без монтажа когда есть товар с монтажом - готово
         * 3) удаление товара с монтажом когда есть товар без монтажа - готово
         * 4) удаление товара с монтажом когда есть товар с монтажом - готово
         *   *** когда оба товара одинакового типа без монтажа ***
         * 5) удаление товара с монтажом когда в заказе есть товар другого типа с монтажом - готово
         * 6) удаление товара без монтажа когда в заказе есть товар другого типа без монтажа - готово
         * 7) удаление товара с монтажом когда в заказе есть товар другого типа без монтажа - готово
         * *** удаление товара без монтажа когда есть товар с монтажом ***
         */

        /**
         * @return void
         * @test
         */
        public function delete_when_order_has_single_product() {
            $this->setUpDefaultActions();
            $this->testHelper->createDefaultOrder();
            $this->testHelper->createDefaultProduct();
            $this->testHelper->createDefaultSalary();

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);
            $this->assertSoftDeleted('products', ['id' => 1])
                ->assertDatabaseHas('orders', [
                    'price' => 0,
                    'measuring_price' => 0,
                    'delivery' => 0,
                    'products_count' => 0,
                ])->assertSoftDeleted('installers_salaries', ['id' => 1])
                ->assertDatabaseCount('installers_salaries', 1);
        }

        /**
         * @test
         * @return void
         */
        public function deleting_product_with_installation_when_order_has_product_of_same_type_with_installation() {
            $this->setUpDefaultActions();

            $data = $this->testHelper->defaultInstallationData();

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($data),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($data),
            ]);

            $this->testHelper->createDefaultSalary(1200);

            $this->testHelper->createDefaultOrder(4510, 0, 2);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 1])
                ->assertDatabaseCount('installers_salaries', 1)
                ->assertDatabaseHas('installers_salaries', ['sum' => 1050])
                ->assertDatabaseHas('orders', [
                    'price' => 2555,
                    'products_count' => 1,
                    'measuring_price' => 0,
                    'delivery' => 600,
                ]);
        }

        /**
         * @test
         * @return void
         */
        public function deleting_product_with_installation_when_order_has_product_with_no_installation() {
            $this->setUpDefaultActions();

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultInstallationData()),
            ]);

            $this->testHelper->createDefaultSalary(1050);
            $this->testHelper->createDefaultSalary(0);

            $this->testHelper->createDefaultOrder(3717, 0, 2);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 2]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 2])
                ->assertDatabaseHas('orders', [
                    'price' => 2362,
                    'products_count' => 1,
                    'measuring_price' => 600,
                    'delivery' => 600,
                ])
                ->assertDatabaseHas('installers_salaries', ['sum' => 960])
                ->assertDatabaseHas('installers_salaries', ['sum' => 0])
                ->assertDatabaseCount('installers_salaries', 2);
        }

        /**
         * @test
         * @return void
         */
        public function deleting_product_when_order_has_product_of_another_type_with_installation() {
            $this->setUpDefaultActions();

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultInstallationData()),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 10,
                'data' => json_decode($this->testHelper->defaultInstallationData(10, 2)),
            ]);

            $this->testHelper->createDefaultSalary($this->testHelper->defaultSalarySum(1));
            $this->testHelper->createDefaultSalary($this->testHelper->defaultSalarySum(1, 2, 10), 7);

            $this->testHelper->createDefaultOrder(6202, 0, 2, 960);
            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 2]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 2])
                ->assertSoftDeleted('installers_salaries', ['sum' => 1100])
                ->assertDatabaseHas('installers_salaries', ['sum' => 1050])
                ->assertDatabaseHas('orders', [
                    'price' => 2555,
                    'measuring_price' => 0,
                    'products_count' => 1,
                    'delivery' => 600,
                ]);
        }

        /**
         * @return void
         * @test
         */
        public function deleting_product_with_no_installation_when_order_has_product_with_installation() {
            $this->setUpDefaultActions();

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 8,
                'data' => json_decode($this->testHelper->defaultInstallationData()),
            ]);

            $this->testHelper->createDefaultSalary(1050);
            $this->testHelper->createDefaultSalary(0);

            $this->testHelper->createDefaultOrder(3717, 0, 2);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 1])
                ->assertDatabaseHas('orders', [
                    'price' => 2555,
                    'measuring_price' => 0,
                    'products_count' => 1,
                ])->assertDatabaseHas('installers_salaries', ['sum' => 1050])
                ->assertDatabaseHas('installers_salaries', ['sum' => 0])
                ->assertDatabaseCount('installers_salaries', 2);
        }

        /**
         * @return void
         * @test
         */
        public function deleting_product_with_no_installation_when_order_has_another_type_with_no_installation() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(4504, 600, 2, 960);
            $this->testHelper->createDefaultSalary(960, 7);
            $this->testHelper->createDefaultSalary(0);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData(1, 2)),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 1])
                ->assertDatabaseHas('orders', [
                    'price' => 2362,
                    'measuring_price' => 600,
                    'products_count' => 1,
                    'delivery' => 600,
                ])
                ->assertNotSoftDeleted('installers_salaries', ['sum' => 960])
                ->assertSoftDeleted('installers_salaries', ['sum' => 960, 'id' => 1])
                ->assertDatabaseCount('installers_salaries', 2);
        }

        /**
         * @return void
         * @test
         */
        public function deleting_with_installation_order_has_product_another_type_no_installation() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(5409, 0, 2, 960);
            $this->testHelper->createDefaultSalary(1100, 7);
            $this->testHelper->createDefaultSalary(0);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 10,
                'data' => json_decode($this->testHelper->defaultInstallationData(10, 2)),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 1])
                ->assertDatabaseHas('orders', [
                    'price' => 2362,
                    'products_count' => 1,
                    'measuring_price' => 600,
                    'delivery' => 600,
                ])->assertSoftDeleted('installers_salaries', ['sum' => 1100])
                ->assertNotSoftDeleted('installers_salaries', ['sum' => 960])
                ->assertDatabaseCount('installers_salaries', 2);
        }

        /**
         * @return void
         * @test
         */
        public function delete_product_with_no_installation_has_product_with_no_installation() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(3524, 600, 2)
                ->createDefaultSalary();

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);

            $this->assertDatabaseHas('orders', [
                'price' => 2362,
                'products_count' => 1,
                'measuring_price' => 600,
                'delivery' => 600,
            ])->assertSoftDeleted('products', ['id' => 1])
                ->assertNotSoftDeleted('installers_salaries', ['sum' => 960])
                ->assertDatabaseCount('installers_salaries', 1);
        }

        /**
         * @return void
         * @test
         */
        public function delete_no_installation_when_has_another_type_with_installation() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(5409, 0, 2, 960)
                ->createDefaultSalary(1100, 7)
                ->createDefaultSalary(0);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => json_decode($this->testHelper->defaultNoInstallationData()),
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 10,
                'data' => json_decode($this->testHelper->defaultInstallationData(10, 2)),
            ]);

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), ['_method' => 'delete']);

            $this->assertSoftDeleted('products', ['id' => 1])
                ->assertNotSoftDeleted('installers_salaries', ['sum' => 1100])
                ->assertSoftDeleted('installers_salaries', ['sum' => 0])
                ->assertDatabaseCount('installers_salaries', 2)
                ->assertDatabaseHas('orders', [
                    'price' => 4247,
                    'products_count' => 1,
                    'measuring_price' => 0,
                    'delivery' => 960,
                ]);
        }
    }
