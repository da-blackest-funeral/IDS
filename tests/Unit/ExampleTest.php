<?php

    namespace Tests\Unit;

    use Tests\TestCase;
    use App\Services\Calculator\Interfaces\Calculator;
    use Tests\CreatesApplication;

    class ExampleTest extends TestCase
    {
        use CreatesApplication;

        public function setUpDefaultActions() {
            $this->createApplication();

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

            $calculator = app(Calculator::class);
            $salary = $calculator->getInstallationSalary(8, 1, 1);

            self::assertTrue($salary->salary == 1050);
        }

        /**
         * @return void
         * @test
         */
        public function salary_for_difficulty() {
            $this->setUpDefaultActions();

            $calculator = app(Calculator::class);
            self::assertTrue($calculator->salaryForDifficulty(1000, 2, 2) == 500);
        }
    }
