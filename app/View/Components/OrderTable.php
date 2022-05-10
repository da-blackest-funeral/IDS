<?php

    namespace App\View\Components;

    use App\Models\Order;
    use Illuminate\View\Component;

    class OrderTable extends Component
    {
        public Order $order;
        /**
         * Create a new component instance.
         *
         * @return void
         */
        public function __construct() {
            $this->order = \OrderHelper::getOrder();
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View|\Closure|string
         */
        public function render() {
            return view('components.order-page.order-table');
        }
    }
