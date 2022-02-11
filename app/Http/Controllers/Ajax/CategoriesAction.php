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

    public function __construct() {
        $this->categoryId = (int) \request()->post('categoryId');
    }

    protected $relations = [
        Category::class => [
            'type',
            'products',
            'tissue',
        ],
    ];

    public function __invoke() {

        $method = $this->getMethod();
        $relationsCount = count($this->relations[strtok($method, '::')]);
        $data = $this->execute($method);
        for ($i = 0; $i < $relationsCount; $i++) {
            $relation = $this->relations[strtok($method, '::')][$i];
            $data = $data->pluck($relation);
            if ($this->uselessCollection($data)) {
                $data = $data[0];
            }
        }

        return view('ajax.mosquito-systems.tissue')
            ->with(compact('data'));
    }

    protected function execute($method) {
        return
            call_user_func(
                $method,
                $this->categoryId
            )->get();
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
}
