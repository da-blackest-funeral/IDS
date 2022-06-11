<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Classes\AbstractProductService;
    use App\Services\Helpers\Interfaces\ProductServiceInterface;
    use Illuminate\Support\Facades\Facade;

    class ProductServiceFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return ProductServiceInterface::class;
        }
    }
