<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\GlazedWindows\Glass;
use App\Models\GlazedWindows\GlazedWindows;
use App\Models\GlazedWindows\WithHeating;
use Illuminate\Http\Request;

class GlazedWindowsController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getLast() {
        $data = [];
        // todo создать таблицу thermo_regulator как в оригинальном сайте
        if ($this->isWithHeating()) {
            $data = WithHeating::all(['id', 'name']);
        } elseif ($this->isGlass()) {
            $data = Glass::query()
                ->select('thickness')
                ->groupBy('thickness')
                ->get();
        }
        \Debugbar::info($data);
        return view('ajax.glazed-windows.last')
            ->with(compact('data'));
    }

    public function additional() {
        // select * from g_w with cameras_width where category_id
        // притом, не забыть про layer - стекло или камера
        // if'ами сделать проверку, является ли это стеклопакетом с подогревом или стеклом, тогда другая логика
        if ($this->isWithHeating()) {
            return $this->withHeating();
        } elseif ($this->isGlass()) {
            return $this->glass();
        } else {
            return $this->glazedWindows();
        }

    }

    protected function isWithHeating(): bool {
        return (int)$this->request->get('categoryId') == 17;
    }

    protected function isGlass(): bool {
        return (int)$this->request->get('categoryId') == 18;
    }

    // todo завершить этот метод
    // todo вывод селекта определяется group_id, опшны в них всегда одинаковые
    protected function withHeating() {
        $widths = WithHeating::has('group')->with('group')->get();
        // если group_id = ..., то $count = 2, иначе если равно ... то $count = 1, иначе = 0.
        // вывод значений от этого не меняется
        // выбирать все терморегуляторы
    }

    protected function glass() {
        return view('ajax.glazed-windows.glass-additional')
            ->with(Glass::all(['id', 'name']));
    }

    protected function glazedWindows() {
        $camerasCount = (int)$this->request->get('additional');
        $glassWidth = GlazedWindows::select(['id', 'name'])
            ->where('layer_id', 2)
            ->get();
        \Debugbar::info($glassWidth);
        $camerasWidth = GlazedWindows::with('camerasWidth')
            ->where('category_id', (int)$this->request->get('categoryId'))
            ->where('layer_id', 1)
            ->get()
            ->pluck('camerasWidth');
        return view('ajax.glazed-windows.additional')
            ->with(compact('camerasWidth', 'camerasCount', 'glassWidth'));
    }
}
