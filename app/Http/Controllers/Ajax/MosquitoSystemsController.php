<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\MosquitoSystems\Profile;
use Illuminate\Http\Request;

class MosquitoSystemsController extends Controller
{
    public function profile(Request $request) {
        $data = Profile::query()
            ->select('name')
            ->whereHas('products.type', function ($query) use ($request) {
                return $query->where('category_id', $request->get('categoryId'));
            })
            ->get();
        \Debugbar::info($data);
        return view('ajax.mosquito-systems.profiles')
            ->with(compact('data'));
    }
}
