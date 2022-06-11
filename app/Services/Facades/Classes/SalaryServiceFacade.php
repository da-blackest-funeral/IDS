<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Classes\SalaryService;
    use Illuminate\Support\Facades\Facade;

    class SalaryServiceFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return SalaryService::class;
        }
    }
