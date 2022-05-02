<?php

    namespace Orders\MosquitoSystems;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
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
         * @test
         * @return void
         */
        public function order_with_mosquito_system_product() {
            $this->setUpDefaultActions();

            $this->post(route('new-order'), $this->testHelper->exampleMosquitoSystemsInputs());

            $this->assertDatabaseHas(
                'orders',
                ['price' => $this->testHelper->productPrice() +
                    $this->testHelper->defaultDeliverySum() +
                    $this->testHelper->measuringPrice()]
            )->assertDatabaseHas(
                'products',
                $this->testHelper->defaultProductInOrder()
            )->assertDatabaseHas(
                'installers_salaries',
                [
                    'sum' =>
                    SystemVariables::value('delivery') +
                    SystemVariables::value('measuringWage')
                ],
            )->assertDatabaseMissing('installers_salaries', ['id' => 2])
                ->assertDatabaseMissing('products', ['id' => 2])
                ->assertDatabaseMissing('orders', ['id' => 2]);
        }



        /**
         * Test when creating two products with no installation
         * salary accrues one time
         *
         * @test
         * @return void
         */
        public function order_with_two_same_products_with_no_installation() {
            $this->setUpDefaultActions();

            $this->post(route('new-order'), $this->testHelper->exampleMosquitoSystemsInputs());
            $this->post(route('order', ['order' => 1]), $this->testHelper->exampleMosquitoSystemsInputs());

            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['price'] = $this->testHelper->productPrice() *
                2 +
                $this->testHelper->defaultDeliverySum() +
                $this->testHelper->measuringPrice();

            $resultOrder['products_count'] = 2;

            $resultProduct1 = $this->testHelper->defaultProductInOrder();
            $resultProduct1['id'] = 1;
            $resultProduct2 = $this->testHelper->defaultProductInOrder();
            $resultProduct2['id'] = 2;

            $resultSalary = $this->testHelper->defaultSalary();

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

            $order = $this->testHelper->defaultOrder();
            $order['discounted_price'] = $this->testHelper->measuringPrice() +
                $this->testHelper->defaultDeliverySum() +
                $this->testHelper->productPrice();
            $order['measuring'] = 0;
            $order['structure'] = 'test';

            Order::create($order);

            $product = $this->testHelper->defaultProductInOrder();
            $product['data'] = '{}';
            ProductInOrder::create($product);

            $salary = $this->testHelper->defaultSalary();
            $salary['comment'] = 'Test comment!';
            $salary['status'] = 'test';
            $salary['changed_sum'] = SystemVariables::value('measuringWage')
                + SystemVariables::value('delivery');
            $salary['type'] = SalaryType::NO_INSTALLATION;
            InstallerSalary::create($salary);

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['categories'] = 7;
            $this->post(route('order', ['order' => 1]), $inputs);

            $resultSalary = $this->testHelper->defaultSalary();
            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['price'] += $this->testHelper->productPrice(1, 1, 2)
                - $this->testHelper->defaultDeliverySum()
                + $this->testHelper->defaultDeliverySum(2);

            $resultOrder['products_count'] = 2;
            $resultOrder['delivery'] = $this->testHelper->defaultDeliverySum(2);

            $resultProduct = $this->testHelper->defaultProductInOrder();
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
                ['sum' => 960, 'id' => 2]
            );
        }

        /*
         * todo написать следующие тесты:
         * Тесты на создание товара - ГОТОВО
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
         * 11) то же самое как в 10, только оба с коэффициентом сложности - готово
         *
         * Тесты на обновление товара:
         * 1) когда обновляешь один товар, и ничего не меняешь, то и результат не должен измениться - готово
         * 2) когда увеличиваешь количество товара - готово
         *   2.1) без монтажа - готово
         *   2.2) с монтажом - готово
         * 3) когда уменьшаешь количество - готово
         *   3.1) без монтажа - готово
         *   3.2) с монтажом - готово
         * 4) когда не было монтажа, поставить монтаж, и при этом в заказе не было товаров с монтажом - готово
         * 5) как п.4, только были товары с монтажом того же типа - готово
         * 6) как п.4, только были товары с монтажом другого типа (и сам товар другого типа) - готово
         * 7) когда был монтаж, поставить без монтажа - готово
         * 8) как п.7, только были товары с монтажом - готово
         * 9) как п.7, только были товары без монтажа
         *
         * на будущее:
         * 1) проверка назначения какому монтажнику присвоен заказ
         *
         * также, продумать тесты с:
         * 1) ремонтом сетки
         * 2) с быстрым созданием
         * 3) со скидками
         * 4) настройкой всего заказа
         * 5) минимальной суммы заказа
         */
    }
