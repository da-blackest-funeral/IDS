<?php

namespace App\Providers;

use App\Services\Classes\GlazedWindowsCalculator;
use App\Services\Interfaces\Calculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Calculator::class, function ($app) {
            if (in_array(request()->input('categories'), [14, 15, 16])) {
                return new GlazedWindowsCalculator(request());
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
