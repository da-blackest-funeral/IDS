<?php

    namespace App\View\Components;

    use App\Models\Order;
    use Illuminate\Support\Collection;
    use Illuminate\View\Component;
    use View;

    class ProductsTable extends Component
    {
        public Collection $products;

        public Order $order;
        /**
         * Create a new component instance.
         *
         * @return void
         */
        public function __construct(Collection $products) {
            $this->products = $products;
            $this->order = \OrderHelper::getOrder();
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View
         */
        public function render() {
            return view('components.products-table');
        }
    }
