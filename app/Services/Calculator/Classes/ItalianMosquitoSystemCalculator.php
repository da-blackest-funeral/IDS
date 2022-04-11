<?php

namespace App\Services\Calculator\Classes;

use App\Models\MosquitoSystems\Italian;
use App\Services\Classes\MosquitoSystemsCalculator;

class ItalianMosquitoSystemCalculator extends MosquitoSystemsCalculator
{
    protected int $dollar = 100; // просто от балды, на будущее

    // todo сделать условие если сетка – антимошка черная или антикошка серая или антимошка серая,
    // то цену увеличить на 30%
    protected function getProductPrice() {
        try {
            $product = Italian::where('height', '>', $this->request->get('height'))
                ->where('width', '>', $this->request->get('width'))
                ->orderBy('height')
                ->orderBy('width')
                ->firstOrFail();

            $this->price += $product->price * $this->dollar;
        } catch (\Exception $exception) {
            \Debugbar::info($exception->getMessage());
        }
    }
}
