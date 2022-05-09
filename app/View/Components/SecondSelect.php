<?php

    namespace App\View\Components;

    use App\Models\ProductInOrder;
    use Illuminate\View\Component;

    class SecondSelect extends Component
    {
        public ?string $link = null;

        public ?ProductInOrder $product = null;

        public ?string $name = null;

        public ?string $label = null;

        public ?string $selected = null;

        public function __construct() {
            //
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View|\Closure|string
         */
        public function render() {
            if (requestHasProduct()) {
                $this->product = requestProduct();

                $attributes = \SelectData::selectAttributes();
                $this->link = $attributes['link'];
                $this->name = $attributes['name'];
                $this->label = $attributes['label'];

                $this->selected = \SelectData::getSelected();
            }

            return view('ajax.second-select')
                ->with([
                    'data' => \SelectData::secondSelect()
                ]);
        }
    }
