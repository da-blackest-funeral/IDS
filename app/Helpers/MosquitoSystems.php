<?php

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use App\Services\Classes\MosquitoSystemsCalculator;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function updateOrCreateSalary(ProductInOrder $productInOrder, Calculator $calculator) {
        $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
            ->whereOrderId($productInOrder->order_id);
        if ($products->exists() && salary($productInOrder)->exists()) {

            /*
             * todo для правильного подсчета з\п при разных монтажах одинакового типа нужно
             * 1) найти какой монтаж максимальный - сделано
             * 2) общее количество товаров с монтажом - сделано
             * 3) записывать в данные о товаре коэффициент сложности монтажа
             *   3.1) создать поле в таблице
             *   3.2) записывать туда коэффициент из request
             *   3.3) при каждом расчете проверять по всем товарам коэффициент сложности монтажа
             */

            $count = countProductsWithInstallation($productInOrder);
            $productsWithMaxInstallation = productsWithMaxInstallation($productInOrder);

            /*
             * orderHasInstallation($order) || $calculator->isNeedInstallation()
             *
             * Условие звучит так: если в заказе уже есть такой же товар с монтажом, и добалвяется
             * товар без монтажа, то зп не пересчитывается. Если в заказе уже есть товар с монтажом, кроме нынешнего,
             * у которого монтаж убирается, то зп тоже не пересчитывается
             *
             * НО, если в заказе уже есть монтаж, а в нынешнем продукте его нет, то з\п должна пересчитываться
             * можно брать $count - request->count()
             */


            /*
             * todo расчет зарплаты при добавлении товаров одинакового типа
             * если добавлено несколько товаров одинакового типа, то при расчете з\п
             * берется монтаж с максимальной ценой, а количество равняется всем товарам данного типа,
             * у которых задан монтаж, даже если он другой
             */

            /*
             * todo проверить, когда разные виды монтажа
             *  и при этом у некоторых есть коэффициент
             */

            // todo когда меняешь в товаре тип монтажа
            // то старый тип еще находит (сделать проверку что это не старый
            // товар через session)

            updateSalary(
                sum: calculateInstallationSalary(
                    calculator: $calculator,
                    productInOrder: $productsWithMaxInstallation->first(),
                    count: $count,
                ),
                productInOrder: $productInOrder,
            );

//            if (
//                !orderHasInstallation($productInOrder->order) ||
//                $calculator->productNeedInstallation()
//            ) {
//                updateSalary(
//                    sum: calculateInstallationSalary(
//                        calculator: $calculator,
//                        productInOrder: $productsWithMaxInstallation->first(),
//                        count: $count,
//                    ),
//                    productInOrder: $productInOrder,
//                );
//            } else {
//                updateSalary(
//                    sum: calculateInstallationSalary(
//                        calculator: $calculator,
//                        productInOrder: $productsWithMaxInstallation->first(),
//                        count: $count,
//                    ),
//                    productInOrder: $productInOrder
//                );
//            }
            /*
             * todo есть два варианта
             * иначе - если у товара был монтаж, а теперь его нет, то отнять от зп:
             * если общее количество товаров в заказе с таким типом монтажа = максимальное (или меньше) количество,
             * за которое идет фиксированная зп, то отнять ее полностью, и вызвать метод для проверки зп за доставку
             * и замер
             *
             * если в заказе суммарно больше товаров с таким типом монтажа, то отнять з\п за доп. товар
             */

            /* (я выбрал этот вариант)
             * иначе:
             * 1) найти товар данного типа с наибольшей ценой монтажа
             * 2) просчитать количество товара с таким типом монтажа
             * 3) зарплату за этот тип приравнять к зарплате за количество из п.2
             */

        } else {
            createSalary($productInOrder->order, $calculator);
        }
    }

    function calculateInstallationSalary(
        MosquitoSystemsCalculator $calculator,
        ProductInOrder            $productInOrder,
        int                       $count,
                                  $installation = null
    ): int {

        if (fromUpdatingProductPage() && oldProductHasInstallation()) {
            $count -= oldProductsCount();
        }

        $salary = $calculator->getInstallationSalary(
            installation: $installation ?? $productInOrder->installation_id,
            count: $count,
            typeId: $productInOrder->type_id
        );

        if ($salary != null) {
            $result = $salary->salary;
        } else {
            $salary = $calculator->maxCountSalary(
                installation: $installation ?? $productInOrder->installation_id,
                typeId: $productInOrder->type_id
            );

            $missingCount = countProductsWithInstallation($productInOrder) - $salary->count;
            // Если это страница обновления товара
            if (fromUpdatingProductPage() && oldProductHasInstallation()) {
                $missingCount -= oldProductsCount();
            }

            $result = $salary->salary + $missingCount * $salary->salary_for_count;

            /*
             * todo коэффициент сложности монтажа
             * В этом методе:
             * 1) в товарах надо сохранять цену монтажа - сделано
             * 2) сохранять коэффициент сложности монтажа - сделано
             * 3) по всем товарам, где есть монтаж:
             *   3.1) если указан коэффициент сложности, вызов метода калькулятора для подсчета - сделано
             */

            /*
             * todo коэф. сложности когда товары разных категорий
             */

            /*
             * todo когда заносятся данные о доп. цене за коэф монтажа
             * нужно умножать на количество, а так же, по моему, неправильно вычисляется именно цена
             * за коэффициент сложности монтажа.
             * Думаю, дело в том, что при обновлении товара не прибавляется цена за коэф. старого товара или что-то в
             *  этом роде.
             */
        }

        foreach (productsWithInstallation($productInOrder) as $product) {
            // todo пропускать старый товар который еще не удален, колхоз, при рефакторинге избавиться от этого
            if (oldProduct('id') == $product->id) {
                continue;
            }

            if (productHasCoefficient($product)) {
                $data = productData($product);

                $result = $calculator->salaryForDifficulty(
                    salary: $result,
                    price: $data->installationPrice,
                    coefficient: $data->coefficient,
                    count: $product->count
                );

                dump($product->getAttributes(), $result);
            }
        }

        return $result;
    }

    function countOfProducts(Collection $products) {
        return $products->sum('count');
    }

    function productHasCoefficient(ProductInOrder $productInOrder) {
        return productData($productInOrder, 'coefficient') > 1;
    }

    function productData(ProductInOrder $productInOrder, string $field = null) {
        if (is_null($field)) {
            return json_decode($productInOrder->data);
        }

        return json_decode($productInOrder->data)->$field;
    }

    function productsWithMaxInstallation(ProductInOrder $productInOrder) {
        $typeId = Type::byCategory($productInOrder->category_id)->id;
        $productsWithInstallation = $productInOrder->order
            ->products()
            ->leftJoin(
                'mosquito_systems_type_additional',
                'mosquito_systems_type_additional.additional_id',
                '=',
                'products.installation_id'
            )
            ->where('mosquito_systems_type_additional.type_id', $typeId)
            // todo оставить только те поля которые нужны
            ->get(['*', 'price as installation_price']);

        $maxInstallationPrice = $productsWithInstallation->max('installation_price');

        return $productsWithInstallation->filter(function ($product) use ($maxInstallationPrice) {
            return $product->installation_price == $maxInstallationPrice;
        });
    }

    function countProductsWithInstallation(ProductInOrder $productInOrder): int {
        return countOfProducts(
            productsWithInstallation($productInOrder)
        );
    }

    /*
     * todo улучшение кода
     * когда буду рефакторить, надо сделать так, чтобы пропускался старый товар (при обновлении который еще не удален)
     * во всех местах где используется этот метод нужно это учесть
     *
     */
    function productsWithInstallation(ProductInOrder $productInOrder): Collection {
        return $productInOrder->order
            ->products()
            ->where('category_id', \request()->input('categories'))
            ->whereNotIn('installation_id', [0, 14])
            ->get();
    }

    function profiles($product = null): Collection {
        $productData = null;

        if (!is_null($product)) {
            $productData = json_decode($product->data);
        }

        return Profile::whereHas('products.type', function ($query) use ($product, $productData) {
            return $query->where('category_id', $product->category_id ?? request()->input('categoryId'))
                ->where('tissue_id', $productData->tissueId ?? request()->input('additional'));
        })
            ->get(['id', 'name']);
    }

    function tissues($categoryId) {
        // todo колхоз
        return \App\Models\Category::tissues($categoryId)
            ->get()
            ->pluck('type')
            ->pluck('products')
            ->collapse()
            ->pluck('tissue')
            ->unique();
    }

    function additional($productInOrder = null) {
        $productData = null;

        if (isset($productInOrder->data)) {
            $productData = json_decode($productInOrder->data);
        }

        $product = Product::whereTissueId($productData->tissueId ?? request()->input('nextAdditional'))
            ->whereProfileId($productData->profileId ?? request()->input('additional'))
            ->whereHas('type', function ($query) use ($productData) {
                $query->where('category_id', $productData->category ?? request()->input('categoryId'));
            })->first();

        $additional = $product->additional;

        $groups = Group::whereHas('additional', function ($query) use ($additional) {
            $query->whereIn('id', $additional->pluck('id'));
        })->get()
            // Заполняем для каждой группы выбранное в заказе значение
            ->each(function ($item) use ($productData) {
                $name = "group-$item->id";
                if (isset($productData) && $productData->$name !== null) {
                    $item->selected = $productData->$name;
                }
            });

        return compact('additional', 'groups', 'product');
    }
