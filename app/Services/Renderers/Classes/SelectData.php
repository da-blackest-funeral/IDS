<?php

    namespace App\Services\Renderers\Classes;

    use App\Models\ProductInOrder;
    use App\Services\Renderers\Interfaces\SelectDataInterface;

    abstract class SelectData implements SelectDataInterface
    {
        public function __construct(protected ProductInOrder $productInOrder) {
        }

        public function use(ProductInOrder $productInOrder): SelectDataInterface {
            $this->productInOrder = $productInOrder;
            return $this;
        }

        public function additional(): array {
            return \ProductService::additional($this->productInOrder);
        }
    }
