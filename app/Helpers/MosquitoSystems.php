<?php

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use App\Services\Calculator\Classes\MosquitoSystemsCalculator;
    use App\Services\Calculator\Interfaces\Calculator;
    use Illuminate\Support\Collection;

    function updateOrCreateSalary(ProductInOrder $productInOrder, Calculator $calculator) {
        $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
            ->whereOrderId($productInOrder->order_id);
        if ($products->exists() && salary($productInOrder)->exists()) {

            $count = countProductsWithInstallation($productInOrder);
            $productsWithMaxInstallation = productsWithMaxInstallation($productInOrder);

            /*
             * Условие звучит так: если в заказе уже есть такой же товар с монтажом, и добалвяется
             * товар без монтажа, то зп не пересчитывается. Если в заказе уже есть товар с монтажом, кроме нынешнего,
             * у которого монтаж убирается, то зп тоже не пересчитывается
             *
             * НО, если в заказе уже есть монтаж, а в нынешнем продукте его нет, то з\п должна пересчитываться
             * можно брать $count - request->count()
             *
             * расчет зарплаты при добавлении товаров одинакового типа
             * если добавлено несколько товаров одинакового типа, то при расчете з\п
             * берется монтаж с максимальной ценой, а количество равняется всем товарам данного типа,
             * у которых задан монтаж, даже если он другой
             */

            updateSalary(
                sum: calculateInstallationSalary(
                    calculator: $calculator,
                    productInOrder: $productsWithMaxInstallation->first(),
                    count: $count,
                ),
                productInOrder: $productInOrder,
            );

            // todo баг
            // когда обновляешь товар и ничего не меняешь то создается новая зарплата

        } elseif (
            // если в заказе есть товары
            $productInOrder->order->products->isNotEmpty() &&
            /*
             * но нет товаров с монтажом, т.е. за
             * доставку з\п уже начислена
             */
            productsWithMaxInstallation($productInOrder)->isEmpty() &&
            // и нынешний товар не нуждается в монтаже
            !$calculator->productNeedInstallation() // todo баг в этом условии
        ) {
            /*
             * то не создавать новую з.п., т.к. за
             * доставку и замер з\п уже должна быть начислена
             */
            return;
        }

        createSalary($productInOrder->order, $calculator);
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

        \Notifier::warning('test it works');

        $salary = $calculator->getInstallationSalary(
            installation: $installation ?? $productInOrder->installation_id,
            count: $count,
            /*
             * todo подумать как это можно исправить
             * обычно, ProductInOrder не имеет поля type_id,
             * но в этот метод передается результат с left join
             */
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
     * когда буду рефакторить, надо сделать так, чтобы пропускался старый товар (при обновлении, который еще не удален)
     * во всех местах где используется этот метод нужно это учесть
     */
    function productsWithInstallation(ProductInOrder $productInOrder): Collection {
        return $productInOrder->order
            ->products()
            ->where('category_id', request()->input('categories'))
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
