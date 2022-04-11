<?php

    namespace App\Providers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Services\Calculator\Classes\GlazedWindowsCalculator;
    use App\Services\Calculator\Classes\ItalianMosquitoSystemCalculator;
    use App\Services\Calculator\Classes\MosquitoSystemsCalculator;
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
            $this->app->bind(\App\Services\Calculator\Interfaces\Calculator::class, function () {
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
