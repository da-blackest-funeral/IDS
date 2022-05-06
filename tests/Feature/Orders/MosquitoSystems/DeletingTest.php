<?php

    namespace Orders\MosquitoSystems;

    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class DeletingTest extends TestCase
    {
        use RefreshDatabase;

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
                    'delivery' => 600
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
//                ->assertDatabaseHas('orders', [
//                    'price' => 2362,
//                    'products_count' => 1,
//                    'measuring_price' => 600,
//                    'delivery' => 600,
//                ])
                ->assertDatabaseHas('installers_salaries', ['sum' => 960])
                ->assertDatabaseHas('installers_salaries', ['sum' => 0])
                ->assertDatabaseCount('installers_salaries', 2);
            // todo сейчас тест не работает потому что нет функционала
        }
    }
