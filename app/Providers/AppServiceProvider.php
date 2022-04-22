<?php

    namespace App\Providers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Services\Calculator\Classes\GlazedWindowsCalculator;
    use App\Services\Calculator\Classes\ItalianMosquitoSystemCalculator;
    use App\Services\Calculator\Classes\MosquitoSystemsCalculator;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\AbstractProductHelper;
    use App\Services\Helpers\Classes\MosquitoSystemsHelper;
    use App\Services\Helpers\Classes\OrderHelper;
    use App\Services\Helpers\Classes\SalaryHelper;
    use App\Services\Helpers\Interfaces\ProductHelper;
    use App\Services\Notifications\Notifier;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         *
         * @return void
         */
        public function register() {
            $this->app->singleton(Calculator::class, function () {
                $request = SaveOrderRequest::createFromBase(\request());
                if (in_array(\request()->input('categories'), [15, 16, 17])) {
                    return new GlazedWindowsCalculator($request);
                }

                if (in_array(\request()->input('categories'), [5, 6, 7, 8, 9, 10, 11, 12, 13, 14])) {
                    return new MosquitoSystemsCalculator($request);
                }

                if (\request()->input('categories') == 13) {
                    return new ItalianMosquitoSystemCalculator($request);
                }
            });

            $this->app->bind(Notifier::class, function () {
                return new Notifier();
            });

            $this->app->singleton(AbstractProductHelper::class, function () {
                if (
                    in_array(\request()->input('categories'), [5, 6, 7, 8, 9, 10, 11, 12, 13, 14])
                    || in_array(\request()->input('categoryId'), [5, 6, 7, 8, 9, 10, 11, 12, 13, 14])
                    || in_array(\request()->productInOrder->category_id, [5, 6, 7, 8, 9, 10, 11, 12, 13, 14])
                ) {
                    return new MosquitoSystemsHelper();
                }
            });

            // todo сделать интерфейсы
            $this->app->bind(OrderHelper::class, OrderHelper::class);
            $this->app->bind(SalaryHelper::class, SalaryHelper::class);
        }

        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot() {
            if (request()->method() == 'POST') {
                \Notifier::setData();
                \Notifier::displayWarnings();
            }
        }
    }
