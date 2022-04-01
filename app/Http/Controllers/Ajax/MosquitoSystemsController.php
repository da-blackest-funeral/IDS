<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\MosquitoSystems\Group;
use App\Models\MosquitoSystems\Profile;
use App\Models\MosquitoSystems\Type;
use App\Models\Service;
use Illuminate\Http\Request;

class MosquitoSystemsController extends Controller
{
    /**
     * Returns profiles for mosquito systems
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function profile(Request $request) {
        \Debugbar::info($request->all());
        $data = Profile::whereHas('products.type', function ($query) use ($request) {
            return $query->where('category_id', $request->get('categoryId'))
                ->where('tissue_id', $request->get('additional'));
        })
            ->get(['id', 'name']);
        return view('ajax.mosquito-systems.profiles')
            ->with(compact('data'));
    }

    public function bracing() {
        return '<div><p class="h3">Пока не готово :-)</p></div>';
        // todo функционал кнопки добавить крепление будет полноценным когда я перенесу таблицу услуг
    }

    /**
     * Returns additional fields
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function additional(Request $request) {
        // для москитных сеток в реквесте должны быть category_id, tissue_id, profile_id
        // а для стеклопакетов количество камер
        // в остальных первые 3 селекта не влияют на вывод дополнительных полей
        $product = Type::where('category_id', (int)$request->get('categoryId'))
            ->with('products', function ($query) use ($request) {
                $query->where('tissue_id', (int)$request->get('nextAdditional'))
                    ->where('profile_id', (int)$request->get('additional'));
            })
            ->get()
            ->pluck('products')->first()->first();

        $additional = $product->additional()->get();
        $groups = Group::whereHas('additional', function ($query) use ($additional) {
            $query->whereIn('id', $additional->pluck('id'));
        })->get();

        \Debugbar::info(compact('product', 'additional', 'groups'));

        return view('ajax.mosquito-systems.additional')->with(
            compact('additional', 'groups', 'product')
        );
    }

    /**
     * Makes from nested collections one with all data
     *
     * @param $nestedCollection
     * @return mixed
     */
    protected function makeCollectionNotNested($nestedCollection) {
        $tmp = [];
        if (isset($nestedCollection[0][0])) {
            foreach ($nestedCollection as $collection) {
                foreach ($collection as $additional) {
                    $tmp[] = $additional;
                }
            }
        }

        return collect($tmp);
    }
}
