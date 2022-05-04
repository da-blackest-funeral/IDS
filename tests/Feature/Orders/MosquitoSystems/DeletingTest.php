<?php

    namespace Orders\MosquitoSystems;

    use App\Models\ProductInOrder;
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

            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "group-1": 6,
                    "group-2": 13,
                    "group-3": 8,
                    "group-4": 38,
                    "category": 5,
                    "delivery": {
                        "additional": 0,
                        "deliveryPrice": ' . $this->testHelper->defaultDeliverySum() . ',
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
                            "text": "Доп. за Монтаж на z-креплениях: ' . $this->testHelper->installationPrice() . '",
                            "price": ' . $this->testHelper->installationPrice() . '
                        },
                        {
                            "text": "Доп. за Пластиковые ручки: 0",
                            "price": 0
                        }
                    ],
                    "main_price": ' . $this->testHelper->productPrice() . ',
                    "coefficient": 1,
                    "installationPrice": ' . $this->testHelper->installationPrice() . '
                }';

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
    }
