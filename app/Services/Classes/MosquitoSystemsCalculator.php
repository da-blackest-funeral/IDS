<?php

namespace App\Services\Classes;

use App\Http\Requests\SaveOrderRequest;
use App\Models\MosquitoSystems\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MosquitoSystemsCalculator extends BaseCalculator
{
    protected Request $request;
    protected float $price = 0.0;
    protected float $squareCoefficient = 1.0;
    protected Collection $options;

    public function __construct(SaveOrderRequest $request) {
        $this->request = $request;
    }

    public function calculate(): void {
        $this->getProductPrice();
    }

    protected function getProductPrice() {

//        \Debugbar::info($this->request);

//        \Debugbar::info(Product::where('tissue_id', $this->request->get('tissues'))->first());
//        \Debugbar::info(
//            Product::where('tissue_id', $this->request->get('tissues'))
//                ->where('category_id', $this->request->get('categories'))
//                ->first()
//        );
        \Debugbar::info($this->request);
        $this->price = Product::where('tissue_id', $this->request->get('tissues'))
            ->where('profile_id', $this->request->get('profiles'))
            ->where('category_id', $this->request->get('categories'))
            ->first()
            ->price;
//        dd($this->price);
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price) {
        // TODO: Implement setPrice() method.
    }

    public function getOptions(): array {
        // TODO: Implement getOptions() method.
    }
}
