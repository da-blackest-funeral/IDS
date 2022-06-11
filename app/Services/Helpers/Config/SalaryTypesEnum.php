<?php

    namespace App\Services\Helpers\Config;

    enum SalaryTypesEnum: string
    {
        case INSTALLATION = 'Монтаж';

        case NO_INSTALLATION = 'Без монтажа';
    }
