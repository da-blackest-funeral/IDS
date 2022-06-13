<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Renderers\Interfaces\SelectDataInterface;

    class SelectDataFacade extends \Illuminate\Support\Facades\Facade
    {
        protected static function getFacadeAccessor() {
            return SelectDataInterface::class;
        }
    }
