<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductConfigurationResource;
use App\Models\Category;

/**
 * This class made for control displaying data
 * when category is chosen in single point
 */
class CategoriesAction extends Controller
{

    /**
     * @var int
     */
    protected int $categoryId;
    /**
     * For getting only related collections, we need to specify all relations
     *
     * @var string[][]
     */
    protected array $relations = [
        Category::class => [
            'type',
            'products',
            'tissue',
        ],
    ];
    /**
     * All concrete information about categories groups
     *
     * @var array
     */
    protected array $categories;

    public function __construct()
    {
        $this->categoryId = (int)\request()->input('categoryId');
        // todo перенести это в json файл
        $this->categories = [
            [
                'link' => '/api/mosquito-systems/profile',
                'name' => 'tissues',
                'ids' => range(5, 14),
                'label' => 'Ткань'
            ],
            [
                'link' => '/api/glazed-windows/last',
                'name' => 'types_window',
                'ids' => range(15, 19),
                'label' => 'Тип окна'
            ],
            [
                'link' => '/api/windowsills/type',
                'name' => 'windowsills',
                'ids' => [20],
                'label' => 'Тип окна',
            ],
        ];
    }

    /**
     * In this main method needed data selects universally from special database table,
     * that specifies method's name to be executed.
     * If method is not specified, returns view with additional data
     */
    public function __invoke()
    {
        $method = $this->getMethod();
        if ($method) {
            $data = $this->execute($method);
        } else {
            /*
             * todo  у некоторых категорий сразу грузятся все дополнительные поля(additional)
             * вместо второго селекта, придумать что с этим делать
             *
             * возможно, в json возвращать type - например, next или final
             */
            return view("ajax.additional.category$this->categoryId-additional");
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

        return response()->json([
            'data' => ProductConfigurationResource::collection($data),
            'link' => $this->category('link'),
            'name' => $this->category('name'),
            'label' => $this->category('label'),
        ]);
    }

    /**
     * Executes specified function and returns selected data
     *
     * @param $method
     * @return false|mixed
     */
    protected function execute($method)
    {
        if (!$this->methodIsAll($method)) {
            return call_user_func($method, $this->categoryId)
                ->get();
        } else {
            return call_user_func($method);
        }
    }

    /**
     * If method is 'all'
     *
     * @param $method
     * @return bool
     */
    protected function methodIsAll($method): bool
    {
        return str_ends_with($method, 'all');
    }

    /**
     * If collection has only one useless nested collection with needed data
     * @TODO убрать это и заменить на ->collapse()
     * @param $data
     * @return bool
     */
    protected function uselessCollection($data): bool
    {
        return isset($data[0]) &&
            is_iterable($data[0]);
    }

    /**
     * Returns method's name for this category
     *
     * @return false|mixed
     */
    protected function getMethod()
    {
        $query = \DB::table('category_has_method')
            ->select('method')
            ->where('category_id', $this->categoryId)
            ->first();
        if ($query !== null) {
            return $query->method;
        }

        return false;
    }

    /**
     * Checks if class of method has specified relations
     *
     * @param $method
     * @return bool
     */
    protected function hasRelations($method): bool
    {
        return isset(
            $this->relations[strtok($method, '::')]
        );
    }

    /**
     * Returns category's data for categories' id
     *
     * @param string $field
     * @return array|false|mixed
     */
    protected function category(string $field = ''): mixed
    {
        foreach ($this->categories as $category) {
            if (in_array($this->categoryId, $category['ids'])) {
                return $category[$field] ?? $category;
            }
        }

        return false;
    }
}
