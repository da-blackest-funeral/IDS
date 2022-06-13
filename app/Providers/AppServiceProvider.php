<?php

    namespace App\Providers;

    use App\Http\Requests\SaveOrderRequest;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Services\Calculator\Classes\GlazedWindowsCalculator;
    use App\Services\Calculator\Classes\ItalianMosquitoSystemCalculator;
    use App\Services\Calculator\Classes\MosquitoSystemsCalculator;
    use App\Services\Calculator\Interfaces\Calculator;
    use App\Services\Helpers\Classes\MosquitoSystemsService;
    use App\Services\Helpers\Classes\OrderService;
    use App\Services\Helpers\Classes\SalaryService;
    use App\Services\Helpers\Interfaces\OrderServiceInterface;
    use App\Services\Helpers\Interfaces\ProductServiceInterface;
    use App\Services\Helpers\Interfaces\SalaryServiceInterface;
    use App\Services\Notifications\Notifier;
    use App\Services\Renderers\Classes\MosquitoSelectData;
    use App\Services\Renderers\Interfaces;
    use App\Services\Repositories\Classes\MosquitoSystemsProductRepository;
    use App\Services\Repositories\Interfaces\ProductRepository;
    use Illuminate\Foundation\Application;
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

            $this->app->singleton(ProductServiceInterface::class, function () {
                if (isMosquitoSystemProduct()) {
                    return new MosquitoSystemsService(
                        request()->productInOrder ?? new ProductInOrder(),
                        request()->order ?? new Order()
                    );
                }
            });

            $this->app->bind(OrderServiceInterface::class, function () {
                return new OrderService(request()->order ?? new Order());
            });

            $this->app->bind(SalaryServiceInterface::class, function () {
                return new SalaryService(request()->order, request()->productInOrder);
            });

            $this->app->bind(SelectDataInterface::class, function () {
                if (isMosquitoSystemProduct()) {
                    return new MosquitoSelectData(\request()->productInOrder);
                }
            });

            $this->app->bind(ProductRepository::class, function (Application $app, array $params) {
//                dump($params);
                return new MosquitoSystemsProductRepository($params['products']);
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
        }
    }
