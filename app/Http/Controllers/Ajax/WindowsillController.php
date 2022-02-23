<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

class WindowsillController extends Controller
{
    public function type() {
        return response()->json([
            'data' => \DB::table('windowsills_material_color')->get(),
            'link' => '/ajax/windowsills/additional',
            'name' => 'load-additional',
        ]);
//        return view('ajax.windowsills.types')->with([
//            'data' => \DB::table('windowsills_material_color')->get(),
//        ]);
    }
}
