<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Interfaces\OrderHelperInterface;
    use Illuminate\Support\Facades\Facade;

    class OrderHelperFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return OrderHelperInterface::class;
        }
    }
