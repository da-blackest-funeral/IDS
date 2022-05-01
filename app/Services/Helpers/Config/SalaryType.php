<?php

    namespace App\Services\Helpers\Config;

    // todo потом сделаю enum когда можно будет нормально обновиться до php 8.1
    use App\Models\ProductInOrder;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class SalaryType
    {
        const INSTALLATION = 'Монтаж';

        const NO_INSTALLATION = 'Без монтажа';

        /**
         * @param ProductInOrder|null $productInOrder
         * @return string
         */
        public static function determine(ProductInOrder $productInOrder = null): string {
            if (is_null($productInOrder)) {

                return Calculator::productNeedInstallation() ?
                    SalaryType::INSTALLATION :
                    SalaryType::NO_INSTALLATION;
            }

            if (\ProductHelper::hasInstallation($productInOrder)) {
                return self::INSTALLATION;
            }

            return self::NO_INSTALLATION;
        }
    }
