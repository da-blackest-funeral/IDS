<?php

    use App\Models\MosquitoSystems\Group;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Profile;
    use App\Models\ProductInOrder;
    use App\Services\Interfaces\Calculator;

    function updateOrCreateSalary(ProductInOrder $productInOrder, Calculator $calculator) {
        $products = ProductInOrder::whereCategoryId($productInOrder->category_id)
            ->whereOrderId($productInOrder->order_id);
        if ($products->exists() && salary($productInOrder)->exists()) {

            $count = productsCount($productInOrder);

            updateSalary(
                $calculator->calculateSalaryForCount($count, $productInOrder),
                $productInOrder
            );

        } else {
            createSalary($productInOrder->order, $calculator);
        }
    }

    function productsCount(ProductInOrder $productInOrder) {
        return $productInOrder
            ->order
            // todo тут ебаная церковь у меня не фильтруются products по category_id
            // из бд поэтому я сделал фильтрацию по коллекции
            ->products
            ->filter(function ($item) {
                return $item->category_id == \request()->input('categories');
            })->sum('count');
    }

    function profiles($product = null) {
        if (isset($product)) {
            $productData = json_decode($product->data);
        } else {
            $productData = null;
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
        if (isset($productInOrder->data)) {
            $productData = json_decode($productInOrder->data);
        } else {
            // todo колхоз
            $productData = null;
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
