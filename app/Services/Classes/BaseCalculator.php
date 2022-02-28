<?php

namespace App\Services\Classes;

use \App\Services\Interfaces\Calculator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

abstract class BaseCalculator implements Calculator
{

    protected Request $request;
    protected float $price = 0.0;
    protected Collection $options;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function setPriceForCount(): void {
        $this->price *= $this->request->get('count');
    }

    public function setRequest(Request $request): void {
        $this->request = $request;
    }

    public function calculate(): void {
        if ($this->hasSquare()) {
            $this->setSquareCoefficient();
        }
    }

    protected function hasSquare(): bool {
        return in_array(HasSquare::class, class_uses($this));
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function getOptions(): Collection {
        return $this->options;
    }
}
