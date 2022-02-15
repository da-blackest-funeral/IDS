<?php

use App\Http\Controllers\Ajax\GlazedWindowsController;
use App\Http\Controllers\Ajax\WindowsillController;
use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Ajax\CategoriesAction;
use \App\Http\Controllers\Ajax\MosquitoSystemsController;
use \App\Http\Controllers\Ajax\AdditionalController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
