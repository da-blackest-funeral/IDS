<?php

    namespace App\View\Components;

    use App\Models\ProductInOrder;
    use Illuminate\View\Component;

    class Additional extends Component
    {
        public ProductInOrder $product;

        /**
         * Create a new component instance.
         *
         * @return void
         */
        public function __construct() {
            $this->product = requestProduct();
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View
         */
        public function render() {
            $additional = \SelectData::additional();
            if (isMosquitoSystemProduct()) {
                return view('ajax.mosquito-systems.additional', [
                    'groups' => $additional['groups'],
                    'additional' => $additional['additional'],
                ]);
            }
        }
    }
