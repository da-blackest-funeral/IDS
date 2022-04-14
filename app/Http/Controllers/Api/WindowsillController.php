<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use function view;

class WindowsillController extends Controller
{
    public function type() {
        return view('ajax.windowsills.types')->with([
            'data' => \DB::table('windowsills_material_color')->get(),
        ]);
    }

    public function additional() {
        return view('ajax.windowsills.additional');
    }


}
