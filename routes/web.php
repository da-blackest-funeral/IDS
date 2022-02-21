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
    Route::get('/', [CalculationController::class, 'index'])
        ->name('welcome');

    Route::view('/documents', 'pages.documents')
        ->name('documents');

    Route::view('/news', 'pages.news')
        ->name('news');

    Route::view('/my-graph', 'pages.my-graph')
        ->name('my-graph'); // страница для монтажника чтобы он мог посмотреть свой график

    Route::view('/installers-graphs', 'pages.installers-graphs')
        ->name('installers-graphs'); // для менеджеров\админов, чтобы смотреть графики всех монтажников

    Route::view('/map', 'pages.map')
        ->name('map');

    Route::view('/entrances', 'pages.entrances')
        ->name('entrances');

    Route::view('/earning', 'pages.earning')
        ->name('earning');

    Route::view('/ratings', 'pages.installers.ratings')
        ->name('ratings');

    Route::prefix('installers')->group(function () {
        Route::view('/notifications', 'pages.installers.notifications')
            ->name('notifications');

        Route::prefix('info')->group(function () {
            Route::view('/', 'pages.installers.info')
                ->name('info');

            Route::view('/map', 'pages.installers.info.map')
                ->name('info-map');

            Route::view('/framed-mosquito-nets', 'pages.installers.info.framed-mosquito-nets')
                ->name('info-framed-mosquito-nets');

            Route::view('/mosquito-doors', 'pages.installers.info.mosquito-doors')
                ->name('info-mosquito-doors');

            Route::view('/sliding-nets','pages.installers.info.sliding-nets')
                ->name('info-sliding-nets');

            Route::view('/rolled-nets', 'pages.installers.info.rolled-nets')
                ->name('info-rolled-nets');

            Route::view('/pleat-grids-italy', 'pages.installers.info.pleat-grids-italy')
                ->name('info-pleat-grids-italy');

            Route::view('/grids-wing', 'pages.installers.info.grids-wing')
                ->name('info-grids-wing');

            Route::view('/trapezoidal-grid', 'pages.installers.info.trapezoidal-grid')
                ->name('info-trapezoidal-grid');

            Route::view('/pluggable-grids-vsn', 'pages.installers.info.pluggable-grids-vsn')
                ->name('info-pluggable-grids-vsn');

            Route::view('/pleat-grids-rus', 'pages.installers.info.pleat-grids-rus')
                ->name('info-pleat-grids-rus');

            Route::view('/hooked-grids', 'pages.installers.info.hooked-grids')
                ->name('info-hooked-grids');
        });
    });

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

        Route::view('/graph', 'pages.management.graph')
            ->name('all-graph');

        Route::prefix('calls')->group(function () {
            Route::view('/', 'pages.management.calls.all')
                ->name('calls');

            Route::view('/settings', 'pages.management.calls.settings')
                ->name('calls-settings');
        });
    });

    Route::prefix('plan')->group(function () {
        Route::view('/', 'pages.plan.my')
            ->name('my-plan');

        Route::view('/all', 'pages.plan.all')
            ->name('all-plan');

        Route::view('/long', 'pages.plan.long')
            ->name('long-plan');

        Route::view('/add', 'pages.plan.add')
            ->name('add-plan');
    });

    Route::prefix('wages')->group(function () {
        Route::view('/managers', 'pages.wages.managers')
            ->name('managers-wages');

        Route::view('/installers', 'pages.wages.installers')
            ->name('installers-wages');

        Route::view('/bonuses', 'pages.wages.bonuses')
            ->name('bonuses');
    });

    Route::prefix('warehouse')->group(function () {
        Route::view('/', 'pages.warehouse.all')
            ->name('warehouses');

        Route::view('/remains', 'pages.warehouse.remains')
            ->name('remains');

        Route::view('/movements-history', 'pages.warehouse.movements-history')
            ->name('movements-history');

        Route::view('/wraps', 'pages.warehouses.wraps')
            ->name('warehouse-wraps');

        Route::view('/template', 'pages.warehouse.template')
            ->name('warehouse-template');

        Route::view('/inventory', 'pages.warehouse.inventory')
            ->name('inventory');

        // выдачи
        Route::view('/issuance', 'pages.warehouse.issuance')
            ->name('issuance');
    });
});

require __DIR__ . '/auth.php';
