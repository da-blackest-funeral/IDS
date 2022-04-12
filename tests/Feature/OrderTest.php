<?php

    namespace Tests\Feature;

    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class OrderTest extends TestCase
    {
        use RefreshDatabase;

        /**
         * Test that user can create order with
         * mosquito systems product with no installation,
         * and it's price calculates properly
         *
         * @test
         * @return void
         */
        public function order_with_mosquito_system_products_price_calculates_properly() {
            $this->seed();
            $this->actingAs(User::first());

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
         * @test
         * @return void
         */
        public function order_with_mosquito_system_product_with_installation_calculates_properly() {
            $this->seed();
            $this->actingAs(User::first());

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
         * @test
         * @return void
         */
        public function order_with_two_same_products_with_no_installation() {
            $this->seed();
            $this->actingAs(User::first());

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

        /*
         * todo написать следующие тесты:
         * 1) когда создаем один товар с монтажом - готово
         * 2) когда создаем несколько товаров одного типа без монтажа - готово
         * 3) когда создаем несколько товаров одного типа, один с монтажом другой без
         * 4) когда создаем несколько товаров одного типа с одинаковым монтажом
         * 5) когда создаем несколько товаров одного типа с разным монтажом
         * 6) когда создаем несколько товаров разных типов без монтажа
         * 7) когда создаем несколько товаров разных типов, и оба с монтажом
         * 8) когда создаем один товар с монтажом и коэффициентом сложности
         * 9) когда создаем несколько товаров одного типа с монтажом, один из них с коэффициентом сложности,
         * а другой без монтажа
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
                'discounted_price' => 2256, // todo поменять когда я сделаю учет скидок
                'status' => 0,
                'measuring_price' => 600,
                'discounted_measuring_price' => 600, // todo скидки
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 1,
                'taken_sum' => 0,
                'installing_difficult' => 1,
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
