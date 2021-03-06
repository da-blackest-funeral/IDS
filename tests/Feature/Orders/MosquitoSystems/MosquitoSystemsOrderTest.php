<?php

    namespace Orders\MosquitoSystems;

    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Services\Helpers\Config\SalaryType;
    use App\Services\Helpers\Config\SalaryTypesEnum;
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

            $this->testHelper->createDefaultOrder(2362)
                ->createDefaultProduct();

            $this->testHelper->createDefaultSalary();

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
            $order['discount'] = 0;
            $order['measuring'] = 0;
            $order['structure'] = 'test';

            Order::create($order);

            $product = $this->testHelper->defaultProductInOrder();
            $product['data'] = json_decode($this->testHelper->defaultNoInstallationData());
            ProductInOrder::create($product);

            $salary = $this->testHelper->defaultSalary();
            $salary['comment'] = 'Test comment!';
            $salary['status'] = 'test';
            $salary['changed_sum'] = SystemVariables::value('measuringWage')
                + SystemVariables::value('delivery');
            $salary['type'] = SalaryTypesEnum::NO_INSTALLATION->value;
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
            $resultProduct['name'] = '?????????????????? ??????????, 25 ??????????????, ?????????????? ????????????????????';
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
         * todo ???????????????? ?????????????????? ??????????:
         * ?????????? ???? ???????????????? ???????????? - ????????????
         * 1) ?????????? ?????????????? ???????? ?????????? ?? ???????????????? - ????????????
         * 2) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ???????? ?????? ?????????????? - ????????????
         * 3) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ????????, ???????? ?? ???????????????? ???????????? ?????? - ????????????
         * 4) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ???????? ?? ???????????????????? ???????????????? - ????????????
         * 5) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ???????? ?? ???????????? ???????????????? - ????????????
         * 6) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ?????????? ?????? ?????????????? - ????????????
         * 7) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ??????????, ?? ?????? ?? ???????????????? - ????????????
         * 8) ?????????? ?????????????? ???????? ?????????? ?? ???????????????? ?? ?????????????????????????? ?????????????????? - ????????????
         * 9) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ???????? ?? ????????????????, ???????? ???? ?????? ?? ?????????????????????????? ??????????????????,
         * ?? ???????????? ?????? ?????????????? - ????????????
         * 10) ?????????? ?????????????? ?????????????????? ?????????????? ???????????? ?????????? ?? ????????????????, ???????? ???? ?????? ?? ?????????????????????????? ??????????????????
         * 11) ???? ???? ?????????? ?????? ?? 10, ???????????? ?????? ?? ?????????????????????????? ?????????????????? - ????????????
         *
         * ?????????? ???? ???????????????????? ????????????:
         * 1) ?????????? ???????????????????? ???????? ??????????, ?? ???????????? ???? ??????????????, ???? ?? ?????????????????? ???? ???????????? ???????????????????? - ????????????
         * 2) ?????????? ???????????????????????? ???????????????????? ???????????? - ????????????
         *   2.1) ?????? ?????????????? - ????????????
         *   2.2) ?? ???????????????? - ????????????
         * 3) ?????????? ???????????????????? ???????????????????? - ????????????
         *   3.1) ?????? ?????????????? - ????????????
         *   3.2) ?? ???????????????? - ????????????
         * 4) ?????????? ???? ???????? ??????????????, ?????????????????? ????????????, ?? ?????? ???????? ?? ???????????? ???? ???????? ?????????????? ?? ???????????????? - ????????????
         * 5) ?????? ??.4, ???????????? ???????? ???????????? ?? ???????????????? ???????? ???? ???????? - ????????????
         * 6) ?????? ??.4, ???????????? ???????? ???????????? ?? ???????????????? ?????????????? ???????? (?? ?????? ?????????? ?????????????? ????????) - ????????????
         * 7) ?????????? ?????? ????????????, ?????????????????? ?????? ?????????????? - ????????????
         * 8) ?????? ??.7, ???????????? ???????? ???????????? ?? ???????????????? - ????????????
         * 9) ?????? ??.7, ???????????? ???????? ???????????? ?????? ??????????????
         *
         * ???? ??????????????:
         * 1) ???????????????? ???????????????????? ???????????? ???????????????????? ???????????????? ??????????
         *
         * ??????????, ?????????????????? ?????????? ??:
         * 1) ???????????????? ??????????
         * 2) ?? ?????????????? ??????????????????
         * 3) ???? ????????????????
         * 4) ???????????????????? ?????????? ????????????
         * 5) ?????????????????????? ?????????? ????????????
         */
    }
