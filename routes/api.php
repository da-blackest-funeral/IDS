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

//Route::get('/product-options', CategoriesAction::class);
