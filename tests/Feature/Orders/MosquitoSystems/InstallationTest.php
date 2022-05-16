<?php

    namespace Orders\MosquitoSystems;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Config\SalaryTypesEnum;
    use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class InstallationTest extends TestCase
    {
        use RefreshDatabase, InteractsWithSession;

        /**
         * Test if mosquito systems product with installation
         * calculates properly
         *
         * @test
         * @return void
         */
        public function order_with_mosquito_system_product_with_installation() {
            $this->setUpDefaultActions();

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;

            $this->post(route('new-order'), $inputs);

            $resultOrder = $this->testHelper->defaultOrder();

            $resultOrder['price'] = $this->testHelper->productPrice() +
                $this->testHelper->installationPrice() +
                $this->testHelper->defaultDeliverySum();
            $resultOrder['discount'] = 0;

            $resultOrder['measuring_price'] = $resultOrder['discounted_measuring_price'] = 0;

            $resultProduct = $this->testHelper->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->testHelper->defaultSalary();
            $resultSalary['sum'] = $this->testHelper->defaultSalarySum(1);

            // todo посмотреть, может быть поле orders.installation вообще не используется и его нужно удалить

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'products',
                $resultProduct
            )->assertDatabaseHas(
                'installers_salaries',
                $resultSalary,
            )->assertDatabaseMissing('installers_salaries', ['id' => 2])
                ->assertDatabaseMissing('products', ['id' => 2])
                ->assertDatabaseMissing('orders', ['id' => 2]);
        }

        /**
         * When creating one product with installation and one without it
         *
         * @test
         * @return void
         */
        public function order_when_creating_one_product_with_installation_and_one_without_it() {
            $this->setUpDefaultActions();

            $inputsWithInstallation = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;
            $price = $this->testHelper->productPrice() + $this->testHelper->measuringPrice() +
                $this->testHelper->defaultDeliverySum();

            $this->testHelper->createDefaultProduct();
            $this->testHelper->createDefaultOrder($price);

            $this->post(route('order', ['order' => 1]), $inputsWithInstallation);

            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['price'] = $price - $this->testHelper->measuringPrice() + $this->testHelper->productPrice() +
                $this->testHelper->installationPrice();
            $resultOrder['products_count'] = 2;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->testHelper->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->testHelper->defaultSalary();
            $resultSalary['sum'] = $this->testHelper->defaultSalarySum(1);

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'products',
                $resultProduct
            )->assertDatabaseHas(
                'installers_salaries',
                $resultSalary
            )->assertDatabaseMissing(
            // testing that salary creates single time
                'installers_salaries',
                ['id' => 2]
            );
        }

        /**
         * Test when creating two products with installation
         * salary must be calculated properly
         *
         * @test
         * @return void
         */
        public function order_when_creating_two_products_with_installation() {
            $this->setUpDefaultActions();

            $inputsWithInstallation = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;

            $this->testHelper->createDefaultOrder(2555, 0)
                ->createDefaultSalary(1050)
                ->createDefaultProduct(8);

            $this->post(route('order', ['order' => 1]), $inputsWithInstallation);

            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['price'] = 2 * (
                    $this->testHelper->installationPrice() +
                    $this->testHelper->productPrice()
                ) + $this->testHelper->defaultDeliverySum();
            $resultOrder['measuring_price'] = 0;
            $resultOrder['products_count'] = 2;

            $resultProduct1 = $this->testHelper->defaultProductInOrder();
            $resultProduct1['id'] = 1;
            $resultProduct1['installation_id'] = 8;
            $resultProduct2 = $resultProduct1;
            $resultProduct2['id'] = 2;

            $resultSalary = $this->testHelper->defaultSalary();
            $resultSalary['sum'] = $this->testHelper->defaultSalarySum(2);

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )
                ->assertDatabaseHas(
                    'products',
                    $resultProduct1
                )->assertDatabaseHas(
                    'products',
                    $resultProduct2
                )->assertDatabaseHas(
                    'installers_salaries',
                    $resultSalary
                )->assertDatabaseMissing(
                // testing that salary creates single time
                    'installers_salaries',
                    ['id' => 2]
                );
        }

        /**
         * Test if in order with 2 products of the same type:
         * 1) When calculates salary takes max installation price
         * 2) When calculates salary count of products equals count of all products that has installation
         *
         * @test
         * @return void
         */
        public function order_when_creating_two_products_with_different_installations() {
            $this->setUpDefaultActions();

            $price = $this->testHelper->productPrice() +
                $this->testHelper->defaultDeliverySum() +
                $this->testHelper->installationPrice(1, 9);

            Order::create([
                'user_id' => 1,
                'delivery' => $this->testHelper->defaultDeliverySum(),
                'installation' => 0,
                'price' => $price,
                'installer_id' => 2,
                'discount' => 0,
                'status' => 0,
                'measuring' => 1,
                'measuring_price' => 0,
                'discounted_measuring_price' => 0,
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 1,
                'taken_sum' => 0,
                'installing_difficult' => 1,
                'is_private_person' => 0,
                'structure' => 'test',
            ]);

            InstallerSalary::create([
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => $this->testHelper->defaultSalarySum(1, 1, 9),
                'created_user_id' => 1,
                'comment' => 'test',
                'status' => 1,
                'changed_sum' => $this->testHelper->defaultSalarySum(1, 1, 9),
                'type' => SalaryTypesEnum::INSTALLATION->value,
            ]);

            $data = json_decode($this->testHelper->defaultInstallationData());

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 9,
                'data' => $data,
            ]);

            $inputsWithInstallation = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;

            $this->post(route('order', ['order' => 1]), $inputsWithInstallation);

            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['price'] = $price + $this->testHelper->productPrice() + $this->testHelper->installationPrice();
            $resultOrder['products_count'] = 2;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->testHelper->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->testHelper->defaultSalary();
            $resultSalary['sum'] = $this->testHelper->defaultSalarySum(2, 1, 9);

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'products',
                $resultProduct
            )->assertDatabaseHas(
                'installers_salaries',
                $resultSalary
            )->assertDatabaseMissing(
                'installers_salaries',
                ['id' => 2]
            );
        }

        /**
         * When creating order with two products of different types
         * with installation, must be created 2 independent salaries
         *
         * @test
         * @return void
         */
        public function order_when_creating_two_products_of_different_types_with_installation() {
            $this->setUpDefaultActions();

            $order = $this->testHelper->defaultOrder();

            $order['price'] = $this->testHelper->productPrice() +
                $this->testHelper->installationPrice() +
                $this->testHelper->defaultDeliverySum();
            $order['discount'] = 0;

            $order['measuring_price'] = $order['discounted_measuring_price'] = $order['measuring'] = 0;
            $order['structure'] = 'test';

            $product = $this->testHelper->defaultProductInOrder();
            $product['installation_id'] = 8;
            $product['data'] = json_decode($this->testHelper->defaultInstallationData());

            $salary = $this->testHelper->defaultSalary();
            $salary['sum'] = $salary['changed_sum'] = $this->testHelper->defaultSalarySum(1);
            $salary['comment'] = 'test';
            $salary['type'] = SalaryTypesEnum::INSTALLATION->value;
            $salary['status'] = 0;

            Order::create($order);

            ProductInOrder::create($product);

            InstallerSalary::create($salary);

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['categories'] = 7;
            $inputs['group-3'] = 10;

            $this->post(route('order', ['order' => 1]), $inputs);

            $resultOrder = $this->testHelper->defaultOrder();

            $resultOrder['price'] = $order['price'] +
                $this->testHelper->productPrice(1, 1, 2) +
                $this->testHelper->installationPrice(2, 10) -
                $this->testHelper->defaultDeliverySum() +
                $this->testHelper->defaultDeliverySum(2);

            $resultOrder['products_count'] = 2;
            $resultOrder['delivery'] = $this->testHelper->defaultDeliverySum(2);
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->testHelper->defaultProductInOrder();
            $resultProduct['category_id'] = 7;
            $resultProduct['name'] = 'Москитные двери, 25 профиль, полотно Антимоскит';
            $resultProduct['installation_id'] = 10;

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'products',
                $resultProduct
            )->assertDatabaseHas(
                'installers_salaries',
                ['sum' => 1050]
            )->assertDatabaseHas(
                'installers_salaries',
                ['sum' => 1100]
            );
        }
    }
