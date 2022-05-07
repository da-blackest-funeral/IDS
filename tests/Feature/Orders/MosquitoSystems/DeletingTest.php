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
         * 2) удаление товара без монтажа когда есть товар с монтажом
         * 3) все комбинации удаления монтаж\без монтажа с другоим товаром в заказе
         * 4) удаление товара когда в заказе есть товар другого типа
         * 5) все комбинации есть\нет монтажа с товаром другого типа
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
         * Test when deleting product with installation when order has product of another type with installation
         *
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

            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "group-1": 6,
                    "group-2": 13,
                    "group-3": 10,
                    "group-4": 38,
                    "category": 5,
                    "delivery": {
                        "additional": 0,
                        "deliveryPrice": ' . $this->testHelper->defaultDeliverySum(2) . ',
                        "additionalSalary": "Нет"
                    },
                    "tissueId": 1,
                    "measuring": 0,
                    "profileId": 1,
                    "additional": [
                        {
                            "text": "Доп. за Z-крепления пластик: 0",
                            "price": 0
                        },
                        {
                            "text": "Доп. за Белый цвет: 0",
                            "price": 0
                        },
                        {
                            "text": "Доп. за Монтаж на z-креплениях: ",
                            "price": ' . $this->testHelper->installationPrice(2, 10) . '
                        },
                        {
                            "text": "Доп. за Пластиковые ручки: 0",
                            "price": 0
                        }
                    ],
                    "main_price": ' . $this->testHelper->productPrice(1, 1, 2) . ',
                    "coefficient": 1,
                    "installationPrice": ' . $this->testHelper->installationPrice(2, 10) . '
                }';

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 7,
                'name' => 'Москитная дверь, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 10,
                'data' => json_decode($data),
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
    }
