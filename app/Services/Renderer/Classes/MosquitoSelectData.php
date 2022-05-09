<?php

    namespace App\Services\Renderer\Classes;

    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\ArrayShape;

    class MosquitoSelectData extends SelectData
    {
        #[ArrayShape(['link' => "string", 'name' => "string", 'label' => "string", 'selected' => "int"])]
        public function selectAttributes() {
            return [
                'link' => '/ajax/mosquito-systems/profile',
                'name' => 'tissues',
                'label' => 'Ткань',
                'selected' => $this->productInOrder->data->tissueId,
            ];
        }

        public function secondSelect(): Collection {
            return \ProductHelper::tissues($this->productInOrder->category_id);
        }

        public function thirdSelect(): Collection {
            return \ProductHelper::profiles($this->productInOrder);
        }
    }
