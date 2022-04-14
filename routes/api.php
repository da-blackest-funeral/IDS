<?php

use App\Http\Controllers\Api\CategoriesAction;
    use App\Http\Controllers\Api\CategoriesController;
    use App\Http\Controllers\Api\GlazedWindowsController;
use App\Http\Controllers\Api\MosquitoSystemsController;
use App\Http\Controllers\Api\WindowsillController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// todo нейминг маршрута говно
Route::get('/product-options', CategoriesAction::class);

Route::get('/categories', [CategoriesController::class, 'index']);

Route::prefix('mosquito-systems')->group(function () {
    Route::get('/profile', [MosquitoSystemsController::class, 'profile']);
    Route::get('/additional', [MosquitoSystemsController::class, 'additional']);
});

Route::prefix('glazed-windows')->group(function () {
    Route::get('/last', [GlazedWindowsController::class, 'getLast']);
    Route::get('/additional', [GlazedWindowsController::class, 'additional']);
});

Route::prefix('windowsills')->group(function () {
    Route::get('/type', [WindowsillController::class, 'type']);
    Route::get('/additional', [WindowsillController::class, 'additional']);
});
