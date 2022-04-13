<?php

    namespace Tests\Feature;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\User;
    use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class MosquitoSystemsOrderTest extends TestCase
    {
        use RefreshDatabase, InteractsWithSession;

        /**
         * Test that user can create order with
         * mosquito systems product with no installation,
         * and it's price calculates properly
         *
         * @return void
         * @test
         */
        public function order_with_mosquito_system_products_price_calculates_properly() {
            $this->setUpDefaultActions();

            $this->post('/', $this->exampleMosquitoSystemsInputs());

            $this->assertDatabaseHas(
                'orders',
                $this->defaultOrder()
            )->assertDatabaseHas(
                'products',
                $this->defaultProductInOrder()
            )->assertDatabaseHas(
                'installers_salaries',
                $this->defaultSalary(),
            )->assertDatabaseMissing('installers_salaries', ['id' => 2])
                ->assertDatabaseMissing('products', ['id' => 2])
                ->assertDatabaseMissing('orders', ['id' => 2]);
        }

        /**
         * Test if mosquito systems product with installation
         * calculates properly
         *
         * @return void
         * @test
         */
        public function order_with_mosquito_system_product_with_installation_calculates_properly() {
            $this->setUpDefaultActions();

            $inputs = $this->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;

            $this->post('/', $inputs);

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = $resultOrder['discounted_price'] = 2376;
            $resultOrder['measuring_price'] = $resultOrder['discounted_measuring_price'] = 0;

            $resultProduct = $this->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 1050;

            // todo почему-то поле order.installation = 0 когда задаешь монтаж в товаре
            // посмотреть, может быть оно вообще не используется и его нужно удалить

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
         * Test when creating two products with no installation
         * salary accrues one time
         *
         * @return void
         * @test
         */
        public function order_with_two_same_products_with_no_installation() {
            $this->setUpDefaultActions();

            $this->post('/', $this->exampleMosquitoSystemsInputs());
            $this->post('/orders/1', $this->exampleMosquitoSystemsInputs());

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 3312;
            $resultOrder['products_count'] = 2;

            $resultProduct1 = $this->defaultProductInOrder();
            $resultProduct1['id'] = 1;
            $resultProduct2 = $this->defaultProductInOrder();
            $resultProduct2['id'] = 2;

            $resultSalary = $this->defaultSalary();

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
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
         * @return void
         * @todo этот функционал работает правильно но тест не проходит
         * дело в том что при двух пост-запросах отправляются одинаковые данные, это не моя ошибка,
         * найти способ как пофиксить
         *
         * ВОЗМОЖНОЕ РЕШЕНИЕ ПРОБЛЕМЫ:
         * заранее создать в тестовой базе заказ и товар, а запрос делать только когда создается второй товар
         *
         *
         * @test
         */
        public function order_when_creating_one_product_with_installation_and_one_without_it() {
            $this->setUpDefaultActions();

            $inputsWithInstallation = $this->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;

            // создаем заранее заказ и один товар, чтобы сделать запрос на уже готовые данные
            $this->createDefaultOrderAndProduct();

            $this->post('/orders/1', $inputsWithInstallation);

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 3432;
            $resultOrder['products_count'] = 2;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 1050;

            $this
                ->assertDatabaseHas(
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

            $inputsWithInstallation = $this->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;

            $this->post('/', $inputsWithInstallation);
            $this->post('/orders/1', $inputsWithInstallation);

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 4152;
            $resultOrder['measuring_price'] = 0;
            $resultOrder['products_count'] = 2;

            $resultProduct1 = $this->defaultProductInOrder();
            $resultProduct1['id'] = 1;
            $resultProduct1['installation_id'] = 8;
            $resultProduct2 = $resultProduct1;
            $resultProduct2['id'] = 2;

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 1200;

            $this
                ->assertDatabaseHas(
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
         * Test if coefficient difficulty works
         *
         * @test
         * @return void
         */
        public function order_with_one_product_with_coefficient_difficulty() {
            $this->setUpDefaultActions();

            $inputsWithInstallation = $this->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;
            $inputsWithInstallation['coefficient'] = 1.5;

            $this->post('/', $inputsWithInstallation);

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 2736;
            $resultOrder['installing_difficult'] = 1.5;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 1230;

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
         * Test if in order with 2 products of the same type:
         * 1) When calculates salary takes max installation price
         * 2) When calculates salary count of products equals count of all products that has installation
         *
         * @test
         * @return void
         */
        public function order_when_creating_two_products_with_different_installations() {
            $this->setUpDefaultActions();

            Order::create([
                'user_id' => 1,
                'delivery' => 600,
                'installation' => 0,
                'price' => 2448,
                'installer_id' => 2,
                'discounted_price' => 2448,
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
                'structure' => '123',
            ]);

            InstallerSalary::create([
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => 1100,
                'created_user_id' => 1,
                'comment' => '123',
                'status' => 1,
                'changed_sum' => 1100,
                'type' => '123'
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 9,
                'data' => '{"coefficient": "1"}',
            ]);

            $inputsWithInstallation = $this->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;

            $this->post('/orders/1', $inputsWithInstallation);

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 4224;
            $resultOrder['products_count'] = 2;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 1250;

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
         * Test when creating order with two products of different types:
         * 1) price calculates properly
         * 2) setting max delivery price
         * 3) salary calculates properly
         *
         * @test
         * @return void
         */
        public function order_with_two_products_of_different_types_with_no_installation() {
            $this->setUpDefaultActions();

            $order = $this->defaultOrder();
            $order['discounted_price'] = 2256;
            $order['measuring'] = 0;
            $order['structure'] = '123';

            Order::create($order);

            $product = $this->defaultProductInOrder();
            $product['data'] = '{"coefficient": "1"}';
            ProductInOrder::create($product);

            $salary = $this->defaultSalary();
            $salary['comment'] = 'Test comment!';
            $salary['status'] = '123';
            $salary['changed_sum'] = 960;
            $salary['type'] = '123';
            InstallerSalary::create($salary);

            $inputs = $this->exampleMosquitoSystemsInputs();
            $inputs['categories'] = 7;
            $this->post('/orders/1', $inputs);

            $resultSalary = $this->defaultSalary();
            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 4236;
            $resultOrder['products_count'] = 2;
            $resultOrder['delivery'] = 960;

            $resultProduct = $this->defaultProductInOrder();
            $resultProduct['name'] = 'Москитные двери, 25 профиль, полотно Антимоскит';
            $resultProduct['category_id'] = 7;

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'installers_salaries',
                $resultSalary
            )->assertDatabaseHas(
                'products',
                $resultProduct
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

            $order = $this->defaultOrder();
            $order['price'] = $order['discounted_price'] = 2376;
            $order['measuring_price'] = $order['discounted_measuring_price'] = $order['measuring'] = 0;
            $order['structure'] = '123';

            $product = $this->defaultProductInOrder();
            $product['installation_id'] = 8;
            $product['data'] = '{"coefficient": "1"}';

            $salary = $this->defaultSalary();
            $salary['sum'] = $salary['changed_sum'] = 1050;
            $salary['comment'] = $salary['type'] = '123';
            $salary['status'] = 0;

            Order::create($order);

            ProductInOrder::create($product);

            InstallerSalary::create($salary);

            $inputs = $this->exampleMosquitoSystemsInputs();
            $inputs['categories'] = 7;
            $inputs['group-3'] = 10;

            $this->post('/orders/1', $inputs);

            $resultOrder = $this->defaultOrder();
            $resultOrder['price'] = 5724;
            $resultOrder['products_count'] = 2;
            $resultOrder['delivery'] = 960;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->defaultProductInOrder();
            $resultProduct['category_id'] = 7;
            $resultProduct['name'] = 'Москитные двери, 25 профиль, полотно Антимоскит';
            $resultProduct['installation_id'] = 10;

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 2150;

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

        /**
         * When creating one product with no installation
         * and one with installation and difficulty
         *
         * @test
         * @return void
         */
        public function order_when_creating_one_product_with_difficulty_and_another_with_no_installation() {
            $this->setUpDefaultActions();
            $this->createDefaultOrderAndProduct();

            $inputs = $this->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;
            $inputs['coefficient'] = 2;

            $this->post('/orders/1', $inputs);

            $resultSalary = $this->defaultSalary();
            $resultSalary['sum'] = 1410;

            $resultOrder = $this->defaultOrder();
            $resultOrder['products_count'] = 2;
            $resultOrder['price'] = 4152;
            $resultOrder['measuring_price'] = 0;

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'installers_salaries',
                $resultSalary
            )->assertDatabaseMissing(
                'installers_salaries',
                ['id' => 2]
            );
        }

        /*
         * todo написать следующие тесты:
         * 1) когда создаем один товар с монтажом - готово
         * 2) когда создаем несколько товаров одного типа без монтажа - готово
         * 3) когда создаем несколько товаров одного типа, один с монтажом другой без - готово
         * 4) когда создаем несколько товаров одного типа с одинаковым монтажом - готово
         * 5) когда создаем несколько товаров одного типа с разным монтажом - готово
         * 6) когда создаем несколько товаров разных типов без монтажа - готово
         * 7) когда создаем несколько товаров разных типов, и оба с монтажом - готово
         * 8) когда создаем один товар с монтажом и коэффициентом сложности - готово
         * 9) когда создаем несколько товаров одного типа с монтажом, один из них с коэффициентом сложности,
         * а другой без монтажа - готово
         * 10) когда создаем несколько товаров разных типов с монтажом, один из них с коэффициентом сложности
         * 11) тест что при добавлении товаров разных типов цена за доставку в order записывается максимальная
         * 12) проверка назначения какому монтажнику присвоен заказ
         *
         * также, продумать тесты с:
         * 1) ремонтом сетки
         * 2) с быстрым созданием
         * 3) со скидками
         * 4) настройкой всего заказа
         * 5) минимальной суммы заказа
         */

        protected function createDefaultOrderAndProduct() {
            Order::create([
                'user_id' => 1,
                'delivery' => 600,
                'installation' => 0,
                'price' => 2256, // todo переписать с учетом минимальной суммы заказа
                'installer_id' => 2,
                'discounted_price' => 2256, // todo поменять когда я сделаю учет скидок
                'status' => 0,
                'measuring_price' => 600,
                'measuring' => 0,
                'discounted_measuring_price' => 600, // todo скидки
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 1,
                'taken_sum' => 0,
                'installing_difficult' => 1, // todo зачем это поле в таблице заказов? по идее оно не нужно
                'is_private_person' => 0,
                'structure' => 'not ready',
            ]);

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                'data' => '{}',
            ]);

            InstallerSalary::create([
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => 960,
                'created_user_id' => 1,
                'comment' => '123',
                'status' => 1,
                'changed_sum' => 1100,
                'type' => '123'
            ]);
        }

        protected function setUpDefaultActions() {
            $this->seed();
            $this->actingAs(User::first());
            $this->withoutExceptionHandling();
        }

        protected function exampleMosquitoSystemsInputs(): array {
            return [
                'height' => 1000,
                'width' => 1000,
                'count' => 1,
                'categories' => 5,
                'tissues' => 1,
                'profiles' => 1,
                'group-1' => 6,
                'group-2' => 13,
                'group-3' => 14,
                'group-4' => 38,
                'add-mount-tools' => 0,
                'coefficient' => 1,
                'new' => 1,
                'fast' => 0,
                'comment' => 'Test Comment!',
            ];
        }

        protected function defaultOrder() {
            return [
                'user_id' => 1,
                'delivery' => 600,
                'installation' => 0,
                'price' => 2256, // todo переписать с учетом минимальной суммы заказа
                'installer_id' => 2,
//                'discounted_price' => 2256, // todo поменять когда я сделаю учет скидок
                'status' => 0,
                'measuring_price' => 600,
//                'discounted_measuring_price' => 600, // todo скидки
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 1,
                'taken_sum' => 0,
                'installing_difficult' => 1, // todo зачем это поле в таблице заказов? по идее оно не нужно
                'is_private_person' => 0,
            ];
        }

        protected function defaultProductInOrder() {
            return [
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                // todo сделать проверку data, возможно через assertTrue
            ];
        }

        protected function defaultSalary() {
            return [
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => 960,
                'created_user_id' => 1,
            ];
        }
    }
