<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\GlazedWindows\Glass;
use App\Models\GlazedWindows\GlazedWindows;
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

    public function additional(Request $request) {
        // select * from g_w with cameras_width where category_id
        // притом, не забыть про layer - стекло или камера
        // if'ами сделать проверку, является ли это стеклопакетом с подогревом или стеклом, тогда другая логика
        $camerasCount = (int)$request->get('additional');
        $glassWidth = GlazedWindows::all(['id', 'name']);
        \Debugbar::info($glassWidth);
        $camerasWidth = GlazedWindows::with('camerasWidth')
            ->where('category_id', (int)$request->get('categoryId'))
            ->get()
            ->pluck('camerasWidth');
        return view('ajax.glazed-windows.additional')
            ->with(compact('camerasWidth', 'camerasCount', 'glassWidth'));
    }
}
