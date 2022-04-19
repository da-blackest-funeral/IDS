<?php

    namespace Orders\MosquitoSystems;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class UpdatingTest extends TestCase
    {
        use RefreshDatabase, InteractsWithSession;

        /**
         * Test when updating product with no changes also
         * order and this product mustn't have changes
         *
         * @test
         * @return void
         */
        public function updating_products_with_no_changes() {
            $this->setUpDefaultActions();
            $resultPrice = $this->testHelper->defaultDeliverySum() +
                $this->testHelper->productPrice() +
                $this->testHelper->measuringPrice();

            $this->testHelper->createDefaultOrder($this->testHelper->defaultDeliverySum() +
                $this->testHelper->productPrice
                () +
                $this->testHelper->measuringPrice()
            );
            $this->testHelper->createDefaultProduct();
            $this->testHelper->createDefaultSalary(SystemVariables::value('measuringWage') + SystemVariables::value('delivery'));

            $this->post(
                route('product-in-order', [
                    'order' => 1, 'productInOrder' => 1,
                ]),
                $this->testHelper->exampleMosquitoSystemsInputs()
            );

            $this->assertDatabaseHas(
                'products',
                $this->testHelper->defaultProductInOrder()
            )->assertDatabaseHas(
                'orders',
                ['price' => $resultPrice]
            );
        }

        /**
         * When updating products and increasing its count
         *
         * @test
         * @return void
         */
        public function updating_products_increasing_count() {
            $this->setUpDefaultActions();

            $price = $this->testHelper->defaultDeliverySum() + $this->testHelper->productPrice() +
                $this->testHelper->measuringPrice();
            $resultPrice = $this->testHelper->defaultDeliverySum() + 2 * $this->testHelper->productPrice() +
                $this->testHelper->measuringPrice();
            $this->testHelper->createDefaultOrder($price);
            $this->testHelper->createDefaultProduct();
            $this->testHelper->createDefaultSalary(SystemVariables::value('measuringWage') + SystemVariables::value('delivery'));

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['count'] = 2;

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), $inputs);

            $this->assertDatabaseHas(
                'orders',
                ['price' => $resultPrice, 'products_count' => 2]
            )->assertDatabaseHas(
                'products',
                ['count' => 2]
            );
        }

        /**
         * When decreasing count of products with no installation
         *
         * @test
         * @return void
         */
        public function updating_products_decreasing_count() {
            $this->setUpDefaultActions();

            $price = $this->testHelper->defaultDeliverySum() + 2 * $this->testHelper->productPrice() +
                $this->testHelper->measuringPrice();
            $resultPrice = $this->testHelper->defaultDeliverySum() + $this->testHelper->productPrice() +
                $this->testHelper->measuringPrice();

            Order::create([
                'user_id' => 1,
                'delivery' => 600,
                'installation' => 0,
                'price' => $price,
                'installer_id' => 2,
                'discounted_price' => $price,
                'status' => 0,
                'measuring_price' => $this->testHelper->measuringPrice(),
                'measuring' => 0,
                'discounted_measuring_price' => $this->testHelper->measuringPrice(),
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 2,
                'taken_sum' => 0,
                'installing_difficult' => 1,
                'is_private_person' => 0,
                'structure' => 'not ready',
            ]);

            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "salary": 960,
                    "group-1": 6,
                    "group-2": 13,
                    "group-3": 14,
                    "group-4": 38,
                    "category": 5,
                    "delivery": {
                        "additional": 0,
                        "deliveryPrice": ' . $this->testHelper->defaultDeliverySum() . ',
                        "additionalSalary": "Нет"
                    },
                    "tissueId": 1,
                    "measuring": ' . $this->testHelper->measuringPrice() . ',
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
                            "text": "Доп. за Без монтажа: 0",
                            "price": 0
                        },
                        {
                            "text": "Доп. за Пластиковые ручки: 0",
                            "price": 0
                        }
                    ],
                    "main_price": ' . 2 * $this->testHelper->productPrice() . ',
                    "coefficient": 1,
                    "installationPrice": 0
                }';

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => $data,
            ]);

            $this->testHelper->createDefaultSalary();

            $this->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]),
                $this->testHelper->exampleMosquitoSystemsInputs());

            $this->assertDatabaseHas(
                'orders',
                ['price' => $resultPrice]
            )->assertDatabaseHas(
                'products',
                ['count' => 1]
            );
        }

        /**
         * When updating product that had installation and after user
         * sets it with no installation
         *
         * @test
         * @return void
         */
        public function updating_products_set_with_no_installation() {
            $this->setUpDefaultActions();

            $price = $this->testHelper->defaultDeliverySum() +
                $this->testHelper->installationPrice() +
                $this->testHelper->productPrice();

            $resultPrice = $this->testHelper->defaultDeliverySum() +
                $this->testHelper->productPrice() +
                $this->testHelper->measuringPrice();

            $this->testHelper->createDefaultOrder($price, 0);

            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "salary": 1050,
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
                'data' => $data,
            ]);

            /*
             * todo баг
             * если обновлять товар несколько раз, меняя туда-сюда монтаж\без монтажа,
             * то в какой то момент цена заказа становится на 600 больше чем должна
             * я думаю дело в замере\доставке, нужно продебажить во время всех таких действий
             * эти атрибуты у товара и заказа и записать на каком этапе это происходит
             *
             * проверить я это смогу когда будет доступен функционал отображения
             */

            $this->testHelper->createDefaultSalary($this->testHelper->defaultSalarySum(1));

            $this->from(route('product-in-order', ['order' => 1, 'productInOrder' => 1]));

            $this->post(
                route(
                    'product-in-order', ['order' => 1, 'productInOrder' => 1]
                ),
                $this->testHelper->exampleMosquitoSystemsInputs()
            );

            $this->assertDatabaseHas(
                'orders',
                ['price' => $resultPrice]
            )->assertDatabaseHas(
                'products',
                ['installation_id' => 14]
            );
        }

        /**
         * Test when decreasing count of products
         * with installation
         * @test
         * @return void
         */
        public function updating_product_with_installation_decreasing_count() {
            $this->setUpDefaultActions();

            $salary = $this->testHelper->defaultSalarySum(2);

            $resultSalary = $this->testHelper->defaultSalarySum(1);

            $deliveryPrice = $this->testHelper->defaultDeliverySum();

            $mainPrice = $this->testHelper->productPrice();

            $installationPrice = $this->testHelper->installationPrice();

            $price = $deliveryPrice + 2 * $mainPrice + 2 * $installationPrice;

            $resultPrice = $deliveryPrice + $mainPrice + $installationPrice;

            $this->testHelper->createDefaultOrder($price, 0, 2);

            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "salary": 1200,
                    "group-1": 6,
                    "group-2": 13,
                    "group-3": 8,
                    "group-4": 38,
                    "category": 5,
                    "delivery": {
                        "additional": 0,
                        "deliveryPrice": ' . $deliveryPrice . ',
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
                            "text": "Доп. за Монтаж на z-креплениях: ' . 2 * $installationPrice . '",
                            "price": ' . 2 * $installationPrice . '
                        },
                        {
                            "text": "Доп. за Пластиковые ручки: 0",
                            "price": 0
                        }
                    ],
                    "main_price": ' . 2 * $mainPrice . ',
                    "coefficient": 1,
                    "installationPrice": ' . $installationPrice . '
                }';

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 2,
                'installation_id' => 8,
                'data' => $data,
            ]);

            $this->testHelper->createDefaultSalary($salary);

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;

            $this->from(
                route('product-in-order', ['order' => 1, 'productInOrder' => 1])
            )->post(
                route('product-in-order', ['order' => 1, 'productInOrder' => 1]),
                $inputs
            );

            $this->assertDatabaseHas(
                'installers_salaries',
                ['sum' => $resultSalary]
            )->assertDatabaseHas(
                'orders',
                ['price' => $resultPrice, 'products_count' => 1, 'measuring_price' => 0]
            );
        }

        /**
         * @test
         * @return void
         */
        public function updating_product_with_installation_increasing_count() {
            $this->setUpDefaultActions();

            $salary = $this->testHelper->defaultSalarySum(1);

            $resultSalary = $this->testHelper->defaultSalarySum(2);

            $deliveryPrice = $this->testHelper->defaultDeliverySum();

            $mainPrice = $this->testHelper->productPrice();

            $installationPrice = $this->testHelper->installationPrice();

            $price = $deliveryPrice + $mainPrice + $installationPrice;

            $resultPrice = $deliveryPrice + 2 * $mainPrice + 2 * $installationPrice;

            $this->testHelper->createDefaultOrder($price, 0, 1);

            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "salary": 1200,
                    "group-1": 6,
                    "group-2": 13,
                    "group-3": 8,
                    "group-4": 38,
                    "category": 5,
                    "delivery": {
                        "additional": 0,
                        "deliveryPrice": ' . $deliveryPrice . ',
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
                            "text": "Доп. за Монтаж на z-креплениях: ' . $installationPrice . '",
                            "price": ' . $installationPrice . '
                        },
                        {
                            "text": "Доп. за Пластиковые ручки: 0",
                            "price": 0
                        }
                    ],
                    "main_price": ' . $mainPrice . ',
                    "coefficient": 1,
                    "installationPrice": ' . $installationPrice . '
                }';

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 2,
                'installation_id' => 8,
                'data' => $data,
            ]);

            $this->testHelper->createDefaultSalary($salary);

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;
            $inputs['count'] = 2;

            $this->from(
                route('product-in-order', ['order' => 1, 'productInOrder' => 1])
            )->post(
                route('product-in-order', ['order' => 1, 'productInOrder' => 1]),
                $inputs
            );

            $this->assertDatabaseHas(
                'installers_salaries',
                ['sum' => $resultSalary]
            )->assertDatabaseHas(
                'orders',
                ['price' => $resultPrice, 'products_count' => 1, 'measuring_price' => 0]
            );
        }

        /**
         * Test when updating product with no installation
         * and set installation to them
         *
         * @test
         * @return void
         */
        public function updating_product_set_with_installation() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder(
                $this->testHelper->measuringPrice() +
                $this->testHelper->productPrice() +
                $this->testHelper->defaultDeliverySum()
            );

            $this->testHelper->createDefaultProduct();

            $this->testHelper->createDefaultSalary(
                $this->testHelper->salaryNoInstallation()
            );

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;

            $this->from(route('product-in-order', ['order' => 1, 'productInOrder' => 1]))
                ->post(route('product-in-order', ['order' => 1, 'productInOrder' => 1]), $inputs);

            $this->assertDatabaseHas(
                'orders',
                [
                    'price' =>
                        $this->testHelper->productPrice() +
                        $this->testHelper->defaultDeliverySum() +
                        $this->testHelper->installationPrice(),
                    'products_count' => 1
                ]
            )->assertDatabaseHas(
                'products',
                ['installation_id' => 8]
            )->assertDatabaseHas(
                'installers_salaries',
                ['sum' => $this->testHelper->defaultSalarySum(1)]
            );
        }
    }
