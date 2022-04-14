<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Notifications\Notifier;
    use Illuminate\Support\Facades\Facade;

    class NotifierFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return Notifier::class;
        }
    }
