<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesAction extends Controller
{
    public function __invoke(Request $request) {
        $model = \DB::table('category_has_model')
            ->select('model')
            ->where('category_id', (int)$request->post('categoryId'))
            ->first()
            ->model;

        return view('ajax.mosquito-systems.tissue')
            ->with([
                'data' => call_user_func($model . '::all')
            ]);
//        return dump(
//            call_user_func($model . '::all')
//        );
    }
}
