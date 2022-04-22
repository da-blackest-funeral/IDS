<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\ProductHelper;

    class ProductFacade
    {
        protected static function getFacadeAccessor() {
            return ProductHelper::class;
        }
    }
