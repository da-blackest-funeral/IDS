<?php

    namespace App\View\Components;

    use Illuminate\View\Component;

    class ThirdSelect extends Component
    {
        /**
         * Create a new component instance.
         *
         * @return void
         */
        public function __construct() {
            //
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View
         */
        public function render() {
            if (isMosquitoSystemProduct()) {
                return view('ajax.mosquito-systems.profiles', [
                    'data' => \SelectData::thirdSelect(),
                    'selected' => requestProduct()->data->profileId,
                ]);
            }
        }
    }
