<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Renderer\Interfaces\SelectDataInterface;

    class SelectDataFacade extends \Illuminate\Support\Facades\Facade
    {
        protected static function getFacadeAccessor() {
            return SelectDataInterface::class;
        }
    }
