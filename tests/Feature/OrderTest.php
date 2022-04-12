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
                $this->orderWithOneMosquitoSystemsProduct()
            )->assertDatabaseHas(
                'products',
                $this->mosquitoSystemProductInOrder()
            )->assertDatabaseHas(
                'installers_salaries',
                $this->mosquitoSystemsInstallerSalaryForOneProduct(),
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

            $resultOrder = $this->orderWithOneMosquitoSystemsProduct();
            $resultOrder['price'] = $resultOrder['discounted_price'] = 2376;
            $resultOrder['measuring_price'] = $resultOrder['discounted_measuring_price'] = 0;

            $resultProduct = $this->mosquitoSystemProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->mosquitoSystemsInstallerSalaryForOneProduct();
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

        /*
         * todo написать следующие тесты:
         * 1) когда создаем один товар с монтажом - готово
         * 2) когда создаем несколько товаров одного типа без монтажа
         * 3) когда создаем несколько товаров одного типа, один с монтажом другой без
         * 4) когда создаем несколько товаров одного типа с одинаковым монтажом
         * 5) когда создаем несколько товаров одного типа с разным монтажом
         * 6) когда создаем несколько товаров разных типов без монтажа
         * 7) когда создаем несколько товаров разных типов, и оба с монтажом
         * 8) когда создаем один товар с монтажом и коэффициентом сложности
         * 9) когда создаем несколько товаров одного типа с монтажом, один из них с коэффициентом сложности,
         * а другой без монтажа
         * 10) когда создаем несколько товаров разных типов с монтажом, один из них с коэффициентом сложности
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

        protected function orderWithOneMosquitoSystemsProduct() {
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

        protected function mosquitoSystemProductInOrder() {
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

        protected function mosquitoSystemsInstallerSalaryForOneProduct() {
            return [
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => 960,
                'created_user_id' => 1,
            ];
        }
    }
