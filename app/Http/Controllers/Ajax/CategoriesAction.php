<?php

    namespace App\Http\Controllers\Ajax;

    use App\Http\Controllers\Controller;

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
         * All concrete information about categories groups
         *
         * @var array
         */
        protected array $categories;

        public function __construct() {
            $this->categoryId = (int)\request()->input('categoryId');
            // todo возможно перенести в json
            $this->categories = [
                [
                    'link' => '/ajax/mosquito-systems/profile',
                    'name' => 'tissues',
                    'ids' => range(5, 14),
                    'label' => 'Ткань',
                ],
                [
                    'link' => '/ajax/glazed-windows/last',
                    'name' => 'types_window',
                    'ids' => range(15, 19),
                    'label' => 'Тип окна',
                ],
                [
                    'link' => '/ajax/windowsills/type',
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
         *
         * @return \Illuminate\Contracts\View\View
         */
        public function __invoke() {
            try {
                $data = call_user_func_array($this->getMethod(), [$this->categoryId]);
            } catch (\Exception $exception) {
                \Debugbar::alert($exception);
                return view("ajax.additional.category$this->categoryId-additional");
            }

            return view('ajax.second-select')->with([
                'data' => $data->unique(),
                'link' => $this->category('link'),
                'name' => $this->category('name'),
                'label' => $this->category('label'),
            ]);
        }

        /**
         * Returns method's name for this category
         *
         * @return false|mixed
         */
        protected function getMethod() {
            return \DB::table('category_has_method')
                ->select('method')
                ->where('category_id', $this->categoryId)
                ->first()
                ->method;
        }

        /**
         * Returns category's data for categories' id
         *
         * @param string $field
         * @return array|false|mixed
         */
        protected function category(string $field = '') {
            foreach ($this->categories as $category) {
                if (in_array($this->categoryId, $category['ids'])) {
                    return $category[$field] ?? $category;
                }
            }

            return false;
        }
    }
