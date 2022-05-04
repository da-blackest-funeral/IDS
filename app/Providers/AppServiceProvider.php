<?php

    namespace App\Providers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Calculator\Classes\GlazedWindowsCalculator;
    use App\Services\Calculator\Classes\ItalianMosquitoSystemCalculator;
    use App\Services\Calculator\Classes\MosquitoSystemsCalculator;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\MosquitoSystemsHelper;
    use App\Services\Helpers\Classes\OrderHelper;
    use App\Services\Helpers\Classes\SalaryHelper;
    use App\Services\Helpers\Interfaces\OrderHelperInterface;
    use App\Services\Helpers\Interfaces\ProductHelperInterface;
    use App\Services\Helpers\Interfaces\SalaryHelperInterface;
    use App\Services\Notifications\Notifier;
    use App\Services\Renderer\Classes\MosquitoSelectData;
    use App\Services\Renderer\Interfaces\SelectDataInterface;
    use Illuminate\Pagination\Paginator;
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

                if (isMosquitoSystemProduct()) {
                    return new MosquitoSystemsCalculator($request);
                }

                if (\request()->input('categories') == 13) {
                    return new ItalianMosquitoSystemCalculator($request);
                }
            });

            $this->app->bind(Notifier::class, function () {
                return new Notifier();
            });

            $this->app->singleton(ProductHelperInterface::class, function () {
                if (isMosquitoSystemProduct()) {
                    return new MosquitoSystemsHelper(
                        request()->productInOrder ?? new ProductInOrder(),
                        request()->order ?? new Order()
                    );
                }
            });

            $this->app->bind(OrderHelperInterface::class, function () {
                return new OrderHelper(\request()->order ?? new Order());
            });

            $this->app->bind(SalaryHelperInterface::class, SalaryHelper::class);

            $this->app->bind(SelectDataInterface::class, function () {
                if (isMosquitoSystemProduct()) {
                    return new MosquitoSelectData(\request()->productInOrder);
                }
            });
        }

        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot() {
            Paginator::useBootstrap();

            if (request()->method() == 'POST') {
                \Notifier::setData();
                \Notifier::displayWarnings();
            }

            $this->callAfterResolving(Calculator::class, function (Calculator $calculator) {
                if (! deletingProduct()) {
                    $calculator->calculate();
                    $calculator->saveInfo();
                }
            });
        }
    }
