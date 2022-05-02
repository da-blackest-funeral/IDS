<?php

    namespace App\Services\Renderer\Classes;

    use Illuminate\Support\Collection;

    class MosquitoSelectData extends SelectData
    {
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
