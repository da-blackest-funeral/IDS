<?php

    namespace App\Services\Renderer\Classes;

    use App\Models\ProductInOrder;
    use App\Services\Renderer\Interfaces\SelectDataInterface;
    use Illuminate\Support\Collection;

    abstract class SelectData implements SelectDataInterface
    {
        public function __construct(protected ProductInOrder $productInOrder) {
        }

        public function use(ProductInOrder $productInOrder): SelectDataInterface {
            $this->productInOrder = $productInOrder;
            return $this;
        }

        public function additional(): array {
            return \ProductHelper::additional($this->productInOrder);
        }
    }
