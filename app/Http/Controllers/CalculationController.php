<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOrderRequest;
use App\Models\Category;
use App\Models\MosquitoSystems\Type;
use App\Models\Order;
use App\Services\Interfaces\Calculator;
use Database\Seeders\GlazedWindowsData;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index(Request $request) {
        return view('welcome')->with([
            'data' => Category::all(),
            'superCategories' => Category::whereIn(
                'id', Category::select(['parent_id'])
                    ->whereNotNull('parent_id')
                    ->groupBy(['parent_id'])
                    ->get()
                    ->toArray()
            )->get(),
            'orderNumber' => Order::count() + 1,
        ]);
    }

    public function save(Calculator $calculator, SaveOrderRequest $request) {
        $calculator->calculate();
        dump($calculator->getPrice());
        dump($request->all());
        dump($calculator->getOptions()->toJson());
    }
}
