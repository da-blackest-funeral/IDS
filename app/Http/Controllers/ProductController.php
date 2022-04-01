<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOrderRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductInOrder;
use App\Models\User;

class ProductController extends Controller
{
    protected SaveOrderRequest $request;

    public function __construct(SaveOrderRequest $request) {
        $this->request = $request;
    }

    public function index(Order $order, ProductInOrder $productInOrder) {
        return view('pages.add-product')->with([
            'data' => Category::all(),
            'superCategories' => Category::whereIn(
                'id', Category::select(['parent_id'])
                ->whereNotNull('parent_id')
                ->groupBy(['parent_id'])
                ->get()
                ->toArray()
            )->get(),
            'orderNumber' => $order->id,
            'product' => $productInOrder,
            'productData' => json_decode($productInOrder->data),
            'needPreload' => true
        ]);
    }

    public function update(Order $order, ProductInOrder $product) {

    }
}
