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

    class DifficultyTest extends TestCase
    {
        use RefreshDatabase, InteractsWithSession;

        /**
         * When creating products of different types and one with difficulty,
         * and another with installation
         *
         * @test
         * @return void
         */
        public function order_when_creating_two_products_of_different_types_one_with_coefficient_difficulty() {
            $this->setUpDefaultActions();
            $coefficient = 2;

            $order = $this->testHelper->defaultOrder();

            $price = $this->testHelper->defaultDeliverySum() +
                $this->testHelper->productPrice() +
                $this->testHelper->installationPrice() *
                $coefficient;

            $order['price'] = $price;
            $order['measuring_price'] = 0;
            $order['discounted_price'] = 0;
            $order['measuring'] = 0;
            $order['structure'] = 'test';
            Order::create($order);

            $product = $this->testHelper->defaultProductInOrder();
            $product['installation_id'] = 8;
            $product['data'] = '{"coefficient": ' . $coefficient . '}';
            ProductInOrder::create($product);

            $salary = $this->testHelper->defaultSalary();
            $salary['sum'] =
            $salary['changed_sum'] =
                $this->testHelper->defaultSalarySum(1) +
                $this->testHelper->installationPrice() *
                (1 - 1 / 2) * $coefficient *
                (float)SystemVariables::value('coefficientSalaryForDifficult');
            $salary['comment'] = '123';
            $salary['category_id'] = 5;
            $salary['status'] = 0;
            $salary['type'] = SalaryType::INSTALLATION;
            InstallerSalary::create($salary);

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['categories'] = 7;
            $inputs['group-3'] = 10;

            $this->post(route('order', ['order' => 1]), $inputs);

            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['delivery'] = $this->testHelper->defaultDeliverySum(2);

            $resultOrder['price'] = $price -
                $this->testHelper->defaultDeliverySum() + $this->testHelper->defaultDeliverySum(2) +
                $this->testHelper->productPrice(1, 1, 2) +
                $this->testHelper->installationPrice(2, 10);

            $resultOrder['measuring_price'] = 0;
            $resultOrder['products_count'] = 2;

            $this->assertDatabaseHas(
                'orders',
                $resultOrder
            )->assertDatabaseHas(
                'installers_salaries',
                ['sum' => $salary['sum']]
            )->assertDatabaseHas(
                'installers_salaries',
                ['sum' => $this->testHelper->defaultSalarySum(1, 2, 10)]
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
            $coefficient = 1.5;

            $inputsWithInstallation = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputsWithInstallation['group-3'] = 8;
            $inputsWithInstallation['coefficient'] = $coefficient;

            $this->post(route('new-order'), $inputsWithInstallation);

            $resultOrder = $this->testHelper->defaultOrder();

            $resultOrder['price'] = $this->testHelper->productPrice() +
                $this->testHelper->defaultDeliverySum() +
                $this->testHelper->installationPrice() * $coefficient;

            $resultOrder['installing_difficult'] = $coefficient;
            $resultOrder['measuring_price'] = 0;

            $resultProduct = $this->testHelper->defaultProductInOrder();
            $resultProduct['installation_id'] = 8;

            $resultSalary = $this->testHelper->defaultSalary();

            $resultSalary['sum'] = (int)round($this->testHelper->defaultSalarySum(1) +
                $this->testHelper->installationPrice() *
                /*
                 * умножение на коэффициент идет потому что
                 * цена на монтаж уже умножена на коэффициент в калькуляторе на этот момент
                 */
                $coefficient *
                (1 - 1 / $coefficient) *
                (float)SystemVariables::value('coefficientSalaryForDifficult'));

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
         * When creating one product with no installation
         * and one with installation and difficulty
         *
         * @return void
         * @todo проверить может такой тест уже есть
         * @test
         */
        public function order_when_creating_one_product_with_difficulty_and_another_with_no_installation() {
            $this->setUpDefaultActions();
            $coefficient = 2;

            $price = $this->testHelper->productPrice() + $this->testHelper->defaultDeliverySum() +
                $this->testHelper->measuringPrice();
            $salary = SystemVariables::value('measuringWage') + SystemVariables::value('delivery');

            $this->testHelper->createDefaultOrder($price);

            $this->testHelper->createDefaultProduct();
            $this->testHelper->createDefaultSalary($salary);

            $inputs = $this->testHelper->exampleMosquitoSystemsInputs();
            $inputs['group-3'] = 8;
            $inputs['coefficient'] = $coefficient;

            $this->post(route('order', ['order' => 1]), $inputs);

            $resultSalary = $this->testHelper->defaultSalary();

            $resultSalary['sum'] = (int)ceil($this->testHelper->defaultSalarySum(1) +
                $this->testHelper->installationPrice() *
                (1 - 1 / 2) *
                (float)SystemVariables::value('coefficientSalaryForDifficult')
                * $coefficient // умножаем на коэффициент
            );

            $resultOrder = $this->testHelper->defaultOrder();
            $resultOrder['products_count'] = 2;
            $resultOrder['price'] = $price - $this->testHelper->measuringPrice() + $this->testHelper->productPrice() +
                $this->testHelper->installationPrice() * $coefficient;
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
    }
