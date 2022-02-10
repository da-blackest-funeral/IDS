<?php

namespace App\Http\Controllers;

use App\Models\MosquitoSystems\Additional;
use App\Models\MosquitoSystems\Group;
use App\Models\MosquitoSystems\Product;
use App\Models\MosquitoSystems\Profile;
use App\Models\MosquitoSystems\Type;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
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

        dump(
          Group::with('types')->first()
        );
    }
}
