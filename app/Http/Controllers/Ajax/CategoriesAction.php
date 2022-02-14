<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoriesAction extends Controller
{

    /**
     * @var int
     */
    private $categoryId;

    protected $categoriesNames;

    protected $relations = [
        Category::class => [
            'type',
            'products',
            'tissue',
        ],
    ];

    public function __construct() {
        $this->categoryId = (int)\request()->post('categoryId');
        $this->categoriesNames = [
            'profile' => range(5, 13),
            'glazed_windows_last' => range(14, 18),
        ];
    }

    public function __invoke() {

        $method = $this->getMethod();
        $data = [];
        if ($method) {
            $data = $this->execute($method);
        }

        if ($this->hasRelations($method)) {
            $relationsCount = count($this->relations[strtok($method, '::')]);
            for ($i = 0; $i < $relationsCount; $i++) {
                $relation = $this->relations[strtok($method, '::')][$i];
                $data = $data->pluck($relation);
                if ($this->uselessCollection($data)) {
                    $data = $data[0];
                }
            }
        }

        /*
         * todo продумать такой момент: если массив data пустой, то нужно возвращать сразу view
         * todo чтобы сделать это универсально, можно называть их как additional.category<id>, например category10
         * todo то, какой селект будет выводиться после выбора во втором селекте, зависит от категории (кроме пленки,
         *  там зависит и от второго селекта). У стеклопакетов вывод дополнительного зависит от количества камер из
         * третьего селекта
         */

        return view('ajax.mosquito-systems.tissue')
            ->with([
                'data' => $data,
                'name' => $this->name()
            ]);
    }

    protected function execute($method) {
        if ($this->methodName($method) != 'all') {
            return call_user_func($method, $this->categoryId)
                ->get();
        } else {
            return call_user_func($method);
        }
    }

    protected function methodName($method) {
        return substr($method, -3);
    }

    protected function uselessCollection($data) {
        return
            isset($data[0]) &&
            is_iterable($data[0]);
    }

    protected function getMethod() {
        $query = \DB::table('category_has_model')
            ->select('method')
            ->where('category_id', $this->categoryId)
            ->first();
        if ($query !== null) {
            return $query->method;
        }

        return false;
    }

    protected function hasRelations($method) {
        return isset(
            $this->relations[strtok($method, '::')]
        );
    }

    protected function name() {
        foreach ($this->categoriesNames as $name => $ids) {
            if (in_array((int)request()->post('categoryId'), $ids)) {
                return $name;
            }
        }

        return false;
    }
}
