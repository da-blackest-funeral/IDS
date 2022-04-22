<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Classes\OrderHelper;
    use Illuminate\Support\Facades\Facade;

    class OrderHelperFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return OrderHelper::class;
        }
    }
