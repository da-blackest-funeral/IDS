<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlazedWindowsController extends Controller
{
    public function getLast(Request $request) {
        $data = [];
        if ((int)$request->get('categoryId') == 17) {
            // data = данные для стеклопакетов с подогревом
        } elseif ((int)$request->get('categoryId') == 18) {
            // data = данные для стекла
        }
        return view('ajax.glazed-windows.last')
            ->with(compact('data'));
    }
}
