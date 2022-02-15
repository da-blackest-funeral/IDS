<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\MosquitoSystems\Type;
use Illuminate\Http\Request;

class AdditionalController extends Controller
{
    // todo у стеклопакетов дополнительное условие на количество камер из 3 столбца
    // todo посмотреть на оригинальном сайте, как подгружаются селекты со значениями ширины и т.д.
    // todo у москитных сеток группы - это селекты, выводящиеся в additional (цвет, крепление и тд),
    // todo а additional - значения, которые там выводятся - например, z-крепления пластик, коричневый цвет и тд
    public function __invoke(Request $request) {
        // для москитных сеток в реквесте должны быть category_id, tissue_id, а для стеклопакетов количество камер
        // в остальных первые 3 селекта не влияют на вывод дополнительных полей
            $groups = Type::where('category_id', (int)$request->get('categoryId'))
                ->with('groups')
                ->get()
                ->pluck('groups')[0];
            $additional = Type::where('category_id', (int)$request->get('categoryId'))
                ->whereHas('products.additional', function ($query) use ($request) {
                    $query->where('tissue_id', (int)$request->get('additional'));
                })->with('additional')
                ->get()
                ->pluck('additional')[0];

//            \Debugbar::info(compact('groups', 'additional'));

            return view('ajax.additional.1')->with(
               compact('groups', 'additional')
            );
        // todo придумать условие вывода опшном в селектах
    }
}
