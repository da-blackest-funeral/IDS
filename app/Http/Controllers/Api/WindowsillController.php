<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use function view;

class WindowsillController extends Controller
{
    public function type() {
        return response()->json([
            'data' => \DB::table('windowsills_material_color')->get(),
        ]);
    }

    public function additional() {
        // todo тут тоже сразу возвращается html
        return view('ajax.windowsills.additional');
    }


}
