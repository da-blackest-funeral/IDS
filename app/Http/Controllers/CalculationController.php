<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Services\Interfaces\Calculator;
use Illuminate\Http\Request;
use function React\Promise\all;

class CalculationController extends Controller
{
    protected Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
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

    public function save(Calculator $calculator) {
//        dd($this->request->all());
        $calculator->calculate();
        dd($calculator->getPrice());
    }
}
