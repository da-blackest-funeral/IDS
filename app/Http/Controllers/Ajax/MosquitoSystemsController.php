<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\MosquitoSystems\Profile;
use App\Models\MosquitoSystems\Type;
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

    // todo у стеклопакетов дополнительное условие на количество камер из 3 столбца
    // todo посмотреть на оригинальном сайте, как подгружаются селекты со значениями ширины и т.д.
    // todo у москитных сеток группы - это селекты, выводящиеся в additional (цвет, крепление и тд),
    // todo а additional - значения, которые там выводятся - например, z-крепления пластик, коричневый цвет и тд
    public function additional(Request $request) {
        // для москитных сеток в реквесте должны быть category_id, tissue_id, а для стеклопакетов количество камер
        // в остальных первые 3 селекта не влияют на вывод дополнительных полей
        $products = Type::where('category_id', (int)$request->get('categoryId'))
            ->with('products.additional.group')
            ->get()
            ->pluck('products');
        $products = $this->makeCollectionNotNested($products);
        $additionalCollections = $products->pluck('additional');
        $additional = $this->makeCollectionNotNested($additionalCollections);
        $groups = $additional->pluck('group')->unique();

        return view('ajax.additional.1')->with(
            compact('additional', 'groups')
        );
    }

    protected function makeCollectionNotNested($nestedCollection) {
        foreach ($nestedCollection as $collection) {
            foreach ($collection as $additional) {
                $tmp[] = $additional;
            }
        }

        return collect($tmp);
    }
}
