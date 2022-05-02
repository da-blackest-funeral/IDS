<?php

    namespace App\Services\Helpers\Config;

    use App\Models\ProductInOrder;
    use App\Services\Repositories\Classes\ProductRepository;
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

            if (
                \ProductHelper::hasInstallation($productInOrder) ||
                ProductRepository::byCategoryWithout($productInOrder)
                    ->has(function ($product) {
                        return \ProductHelper::hasInstallation($product);
                    })
            ) {
                return self::INSTALLATION;
            }

            return self::NO_INSTALLATION;
        }
    }
