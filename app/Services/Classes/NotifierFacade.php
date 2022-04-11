<?php

    namespace App\Services\Classes;

    use Illuminate\Support\Facades\Facade;

    class NotifierFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return Notifier::class;
        }
    }
