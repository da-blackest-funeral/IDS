<?php

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use App\Services\Interfaces\Calculator;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function updateOrCreateSalary(ProductInOrder $productInOrder, Calculator $calculator) {
        $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
            ->whereOrderId($productInOrder->order_id);
        if ($products->exists() && salary($productInOrder)->exists()) {

            $count = productsWithInstallationCount($productInOrder);
//            if ($calculator->productNeedInstallation()) {
//                $count -= oldProductsCount();
//            }

//            dd($count);

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
             * todo баг
             * если уже есть товары с монтажом, и при этом на товаре без монтажа увеличить количество, то, видимо,
             * его количество отнимается от числа товаров с монтажом, поэтому з\п меньше чем нужно
             */

//            dd(orderHasInstallation($productInOrder->order), $calculator->productNeedInstallation(), $count, oldProductsCount());

            if (
                !orderHasInstallation($productInOrder->order) ||
                $calculator->productNeedInstallation()
            ) {
                updateSalary(
                    sum: $calculator->calculateSalaryForCount($count, $productInOrder),
                    productInOrder: $productInOrder
                );
            } else {
                $products = productsWithMaxInstallation($productInOrder);
                $count = countMaxInstallation($products);
//                dd($count);
//                if (oldProductHasInstallation()) {
//                    $count -= oldProductsCount();
//                }
                /*
                 * todo если товар остается единственным с монтажом, то код сюда даже не заходит
                 */
                updateSalary(
                    calculateInstallationSalary(
                        calculator: $calculator,
                        productInOrder: $products->first(),
                        count: $count,
                    ),
                    $productInOrder
                );
            }
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

    function calculateInstallationSalary(Calculator $calculator, ProductInOrder $productInOrder, $count): int {
        // todo !!! нарушение DRY, в калькуляторе уже есть похожий метод, придумать как избавиться от дублирования
        $salary = $calculator->getInstallationSalary(
            installation: $productInOrder->installation_id,
            count: $count,
            typeId: $productInOrder->type_id
        );

        if ($salary != null) {
            return $salary->salary;
        } else {
            $salary = $calculator->maxCountSalary(
                installation: $productInOrder->installation_id,
                typeId: $productInOrder->type_id
            );

            $missingCount = productsWithInstallationCount($productInOrder) - $salary->count;
            // Если это страница обновления товара
            if (fromUpdatingProductPage() && oldProductHasInstallation()) {
                $missingCount -= oldProductsCount();
            }

            return $salary->salary + $missingCount * $salary->salary_for_count;
        }
    }

    function countMaxInstallation(\Illuminate\Support\Collection $products) {
        return $products->sum('count');
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

    function productsWithInstallationCount(ProductInOrder $productInOrder) {
        return $productInOrder->order
            ->products()
            ->where('category_id', \request()->input('categories'))
            ->whereNotIn('installation_id', [0, 14])
            ->get()
            ->sum('count');
    }

    function profiles($product = null) {
        $productData = null;

        if (isset($product)) {
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
