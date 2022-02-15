<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\GlazedWindows\Glass;
use App\Models\GlazedWindows\WithHeating;
use Illuminate\Http\Request;

class GlazedWindowsController extends Controller
{
    public function getLast(Request $request) {
        $data = [];
        // если это стеклопакет с подогревом
        // todo создать таблицу thermo_regulator как в оригинальном сайте
        if ((int)$request->get('categoryId') == 17) {
            $data = WithHeating::all(['id', 'name']);
        } elseif ((int)$request->get('categoryId') == 18) { // если это стекло
            $data = Glass::query()
                ->select('thickness')
                ->groupBy('thickness')
                ->get();
        }
        \Debugbar::info($data);
        return view('ajax.glazed-windows.last')
            ->with(compact('data'));
    }
}
