<?php

namespace App\Providers;

use App\Http\Requests\SaveGlazedWindowsOrderRequest;
use App\Http\Requests\SaveOrderRequest;
use App\Services\Classes\GlazedWindowsCalculator;
use App\Services\Interfaces\Calculator;
use Illuminate\Http\Request;
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
            // todo потом убрать 5
            if (in_array(request()->input('categories'), [14, 15, 16])) {
                return new GlazedWindowsCalculator(new SaveOrderRequest());
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
