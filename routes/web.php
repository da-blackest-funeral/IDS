<?php

use App\Http\Controllers\Ajax\CategoriesAction;
use App\Http\Controllers\Ajax\GlazedWindowsController;
use App\Http\Controllers\Ajax\MosquitoSystemsController;
use App\Http\Controllers\Ajax\WindowsillController;
use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [CalculationController::class, 'index']);

    Route::prefix('ajax')->group(function () {
        Route::get('/get-items', CategoriesAction::class);

        Route::prefix('mosquito-systems')->group(function () {
            Route::get('/profile', [MosquitoSystemsController::class, 'profile']);
            Route::get('/additional', [MosquitoSystemsController::class, 'additional']);
        });

        Route::prefix('glazed-windows')->group(function () {
            Route::get('/last', [GlazedWindowsController::class, 'getLast']);
            Route::get('/additional', [GlazedWindowsController::class, 'additional']);
        });

        Route::prefix('windowsill')->group(function () {
            Route::get('/type', [WindowsillController::class, 'type']);
        });
    });

    Route::prefix('orders')->group(function () {
        Route::view('/add', 'pages.orders.add')
            ->name('add-order');

        Route::view('/all', 'pages.orders.all')
            ->name('all-orders');

        Route::view('/windowsills', 'pages.orders.windowsills')
            ->name('windowsills-orders');

        Route::view('/glazed-windows', 'pages.orders.glazed-windows')
            ->name('glazed-windows-orders');
    });

    Route::prefix('calculations')->group(function () {
        Route::view('/all', 'pages.calculations.all')
            ->name('all-calculations');
    });

    Route::prefix('statistics')->group(function () {
        Route::view('/control', 'pages.statistics.control')
            ->name('control');

        Route::view('/', 'pages.statistics.all')
            ->name('statistics');

        Route::view('/plan', 'pages.statistics.plan')
            ->name('statistics-plan');
    });

    Route::prefix('management')->group(function () {
        Route::view('/prices', 'pages.management.prices')
            ->name('prices');

        Route::view('/users', 'pages.management.users')
            ->name('users');

        Route::view('/money', 'pages.management.money')
            ->name('money');

        Route::view('/additional', 'pages.management.additional')
            ->name('management-additional');

        Route::view('/production', 'pages.management.production')
            ->name('production');

        Route::prefix('calls')->group(function () {
            Route::view('/', 'pages.management.calls.all')
                ->name('calls');

            Route::view('/settings', 'pages.management.calls.settings')
                ->name('calls-settings');
        });

        Route::view('/graph', 'pages.management.graph')
            ->name('all-graph');
    });

});

require __DIR__ . '/auth.php';
