<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Classes\AbstractProductHelper;
    use App\Services\Helpers\Interfaces\ProductHelperInterface;
    use Illuminate\Support\Facades\Facade;

    class ProductHelperFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return ProductHelperInterface::class;
        }
    }
