<?php

    namespace Tests\Unit;

    use App\Services\Calculator\Classes\MosquitoSystemsCalculator;
    use Tests\TestCase;
    use Tests\CreatesApplication;

    class MosquitoSystemsCalculatorTest extends TestCase
    {
        use CreatesApplication;

        public function setUpDefaultActions() {
            \Artisan::call('migrate:fresh');
            \Artisan::call('db:seed');

            $requestData = [
                'categories' => 5,
                'tissues' => 1,
                'profiles' => 1,
            ];

            request()->merge($requestData);
        }

        /**
         * A basic unit test example.
         *
         * @test
         * @return void
         */
        public function get_installation_salary() {
            $this->setUpDefaultActions();

            $calculator = new MosquitoSystemsCalculator(request());
            $salary = $calculator->getInstallationSalary(8, 1, 1);

            self::assertTrue($salary->salary == 1050);
        }

        /**
         * @return void
         * @test
         */
        public function salary_for_difficulty() {
            $this->setUpDefaultActions();

            $calculator = new MosquitoSystemsCalculator(request());
            self::assertTrue($calculator->salaryForDifficulty(1000, 2, 2) == 500);
        }

        /**
         * @return void
         * @test
         */
        public function calculate_salary_for_count() {
            $this->setUpDefaultActions();

            $this->testHelper->createDefaultOrder();

            $calculator = new MosquitoSystemsCalculator(request());
            self::assertTrue($calculator->calculateSalaryForCount(
                count: 5,
                productInOrder: $this->testHelper->createDefaultProduct(8, 1, 5)
            ) == $this->testHelper->defaultSalarySum(2) + 120 * 3);
        }
    }
