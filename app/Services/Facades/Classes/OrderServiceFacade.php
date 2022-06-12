<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Interfaces\OrderServiceInterface;
    use Illuminate\Support\Facades\Facade;

    class OrderServiceFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return OrderServiceInterface::class;
        }
    }
