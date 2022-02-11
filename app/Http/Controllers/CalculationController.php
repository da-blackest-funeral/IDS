<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CalculationController extends Controller
{
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
        ]);
//        $product = Product::first();
//        dump($product->load(['tissue', 'type']));

//        dump(
//          Profile::where('id', '49')->with('products')->get()
//        );

//        dump(
//            Additional::with('products')->first()
//        );

//        dump(
//            Product::with('additional')->first()
//        );

//        dump(
//            Type::with('additional')->first()
//        );

//        dump(
//          Group::with('types')->first()
//        );
    }
}
