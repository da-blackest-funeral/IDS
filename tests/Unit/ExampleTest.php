<?php

    namespace Tests\Unit;

    use App\Models\User;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use PHPUnit\Framework\TestCase;
    use App\Services\Calculator\Interfaces\Calculator;
    use Tests\CreatesApplication;

    class ExampleTest extends TestCase
    {
        use CreatesApplication, DatabaseMigrations, RefreshDatabase;

        public function setUpDefaultActions() {
            $this->createApplication();
            \Artisan::call('migrate');
            \Artisan::call('db:seed');
        }

        /**
         * A basic unit test example.
         *
         * @test
         * @return void
         */
        public function get_installation_salary() {
            $this->setUpDefaultActions();

            $requestData = [
                'categories' => 5,
                'tissues' => 1,
                'profiles' => 1,
            ];

            request()->merge($requestData);

            $calculator = app(Calculator::class);
            $salary = $calculator->getInstallationSalary(8, 1, 1);

            self::assertTrue($salary->salary == 1050);
        }
    }
