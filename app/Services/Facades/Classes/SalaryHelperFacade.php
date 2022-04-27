<?php

    namespace App\Services\Facades\Classes;

    use App\Services\Helpers\Classes\SalaryHelper;
    use Illuminate\Support\Facades\Facade;

    class SalaryHelperFacade extends Facade
    {
        protected static function getFacadeAccessor() {
            return SalaryHelper::class;
        }
    }
