<?php

    namespace App\Services\Renderer\Classes;

    use App\Models\ProductInOrder;
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\Pure;

    class MosquitoSelectData extends SelectData
    {
        protected ProductInOrder $productInOrder;

        public function __construct(ProductInOrder $productInOrder) {
            $this->productInOrder = $productInOrder;
        }

        public function selectAttributes() {
            return [
                'link' => '/ajax/mosquito-systems/profile',
                'name' => 'tissues',
                'label' => 'Ткань'
            ];
        }

        public function getSelected() {
            return $this->productInOrder->data->tissueId;
        }

        public function secondSelect(): Collection {
            return \ProductHelper::tissues($this->productInOrder->category_id);
        }

        public function thirdSelect(): Collection {
            return \ProductHelper::profiles($this->productInOrder);
        }

        public function additional(): array {
            return \ProductHelper::additional($this->productInOrder);
        }
    }
