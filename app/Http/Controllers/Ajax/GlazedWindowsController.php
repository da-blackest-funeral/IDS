<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\GlazedWindows\Glass;
use Illuminate\Http\Request;

class GlazedWindowsController extends Controller
{
    public function getLast(Request $request) {
        $data = [];
        // если это стеклопакет с подогревом
        // todo создать таблицу thermo_regulator как в оригинальном сайте
        if ((int)$request->get('categoryId') == 17) {
            // data = данные для стеклопакетов с подогревом
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
