<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\MosquitoSystems\Profile;
use App\Models\MosquitoSystems\Type;
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
        $products = Type::where('category_id', (int)$request->get('categoryId'))
            ->has('products.additional.group')
            ->with('products.additional.group')
            ->whereHas('products.additional.group', function ($query) use ($request) {
                $query->where('tissue_id', $request->get('nextAdditional'))
                    ->where('profile_id', $request->get('additional'));
            })
            ->get()
            ->pluck('products');
        \Debugbar::info($products);
        $products = $this->makeCollectionNotNested($products);
        $additionalCollections = $products->pluck('additional');
        $additional = $this->makeCollectionNotNested($additionalCollections)->unique('name');
        $groups = $additional->pluck('group')->unique();

        \Debugbar::info(compact('additional', 'groups'));
        return view('ajax.mosquito-systems.additional')->with(
            compact('additional', 'groups')
        );
    }

    /**
     * Makes from nested collections one with all data
     *
     * @param $nestedCollection
     * @return \Illuminate\Support\Collection
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
