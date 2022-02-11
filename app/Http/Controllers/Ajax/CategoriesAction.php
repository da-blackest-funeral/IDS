<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MosquitoSystems\Product;
use App\Models\MosquitoSystems\Tissue;
use Illuminate\Http\Request;

class CategoriesAction extends Controller
{

    protected $relations = [
        Category::class => [
            'type',
            'products',
            'tissue',
        ],
    ];

    public function __invoke(Request $request) {

        $method = \DB::table('category_has_model')
            ->select('method')
            ->where('category_id', (int)$request->post('categoryId'))
            ->first()
            ->method;

        $data = call_user_func($method, (int)$request->post('categoryId'))->get();
        if (isset($data[0]))
            $result = clone $data[0];
        else
            $result = clone $data;
        $end = [];
//        \Debugbar::info(Product::find(1)->tissue()->get());
//        \Debugbar::info($result);
        for ($i = 0; $i < count($this->relations[strtok($method, '::')]); $i++) {
            $relation = $this->relations[strtok($method, '::')][$i];
            if (is_iterable($result)) {
                \Debugbar::info('is iterable ' . $i);
                \Debugbar::info($result);
                // Надо избавиться от N+1 query.
                // Имеем: коллекцию результатов, в каждом из них
                // можно вытащить айдишник, и сделать запрос whereIn
                foreach ($result as $item) {
                    if ($item->$relation() !== null) {
//                        \Debugbar::info($item->$relation()->get());
                        if ($i == count($this->relations[strtok($method, '::')]) - 1) {
                            $end[] = $item->with($relation)->get()[0];
                        } else {
                            $data[] = $item->with($relation)->get();
                        }
//                        \Debugbar::info('here');
                    }
                }
            } else {
                if ($result->$relation() !== null) {
                    $data = $result->getRelation($relation);
                }
            }
            $result = $data;
//            \Debugbar::info($result);
        }
        \Debugbar::info($end);
        $data = $end;

//        \Debugbar::info($data->pluck('type'));

//        foreach ($data as $item) {
//            foreach ($this->relations[strtok($method, '::')] as $relation) {
//                if ($item->$relation()->exists()) {
//                    $data = $item->getRelation($relation);
//                }
//            }
//        }
//        \Debugbar::info($data);

        return view('ajax.mosquito-systems.tissue')
            ->with(compact('data'));
//        return dump(
//            call_user_func($model . '::all')
//        );
    }
}
