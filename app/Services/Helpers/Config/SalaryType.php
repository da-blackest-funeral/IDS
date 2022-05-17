<?php

    namespace App\Services\Helpers\Config;

    use App\Models\ProductInOrder;
    use App\Services\Repositories\Classes\ProductRepository;
    use Facades\App\Services\Calculator\Interfaces\Calculator;

    class SalaryType
    {
        /**
         * @param ProductInOrder|null $productInOrder
         * @return string
         */
        public static function determine(ProductInOrder $productInOrder = null): string {
            if (is_null($productInOrder)) {
                return Calculator::productNeedInstallation() ?
                    SalaryTypesEnum::INSTALLATION->value :
                    SalaryTypesEnum::NO_INSTALLATION->value;
            }

            if (
                \ProductHelper::hasInstallation($productInOrder) ||
                ProductRepository::byCategoryWithout($productInOrder)
                    ->without(oldProduct())
                    ->hasInstallation()
            ) {
                return SalaryTypesEnum::INSTALLATION->value;
            }

            return SalaryTypesEnum::NO_INSTALLATION->value;
        }
    }
