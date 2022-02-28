<?php

namespace App\Services\Classes;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;

abstract class BaseCalculator implements \App\Services\Interfaces\Calculator
{

    protected Request $request;
    protected float $price = 0.0;
    protected Collection $options;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function setPriceForCount() {
        $this->price *= $this->request->get('count');
    }

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function calculate(): void {
        if (is_a($this, HasSquare::class)) {
            $this->setSquareCoefficient();
        }
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function getOptions(): Collection {
        return $this->options;
    }
}
