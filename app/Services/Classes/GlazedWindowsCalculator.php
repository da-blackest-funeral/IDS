<?php

namespace App\Services\Classes;

use App\Http\Requests\SaveOrderRequest;
use App\Models\GlazedWindows\Additional;
use App\Models\GlazedWindows\GlazedWindows;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GlazedWindowsCalculator implements \App\Services\Interfaces\Calculator
{
    protected Collection $options;
    protected float $price = 0.0;
    protected float $squareCoefficient = 1.0;
    protected Collection $additional;
    protected Request $request;

    public function __construct(SaveOrderRequest $request) {
        $this->request = $request;
        $this->additional = Additional::groupBy('name')
            ->get('name')
            ->pluck('name');
    }

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function calculate(): void {
        $this->calculateGlazedWindowsPrice();
        $this->setSquareCoefficient();
        $this->setAdditionalPrice();
        $this->setPriceForCount();
        $this->additionalPriceForSquare();
    }

    protected function setAdditionalPrice() {
        foreach ($this->additional as $field) {
            for ($i = 1; $i <= 3; $i++) {
                $selectName = $field . '-' . $i;
                if ($this->request->has($selectName)) {
                    $this->price += $this->request->get($selectName) * $this->squareCoefficient;
                }
            }
        }
    }

    protected function additionalPriceForSquare() {
        if ($this->squareCoefficient > 2) {
            $this->price *= 1.2;
        }

        if ($this->squareCoefficient > 4) {
            $this->price *= 1.25;
        }
    }

    protected function setPriceForCount() {
        $this->price *= $this->request->get('count');
    }

    protected function getIds(): array {
        $result = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($this->request->has("glass-width-$i")) {
                $result[] = $this->request->get("glass-width-$i");
            }

            if ($this->request->has("cameras-width-$i")) {
                $result[] = $this->request->get("cameras-width-$i");
            }
        }

        return $result;
    }

    protected function calculateGlazedWindowsPrice() {
        foreach ($this->getIds() as $id) {
            $this->price += GlazedWindows::find($id, ['price'])->price;
        }
    }

    protected function setSquareCoefficient() {
        $square = $this->request->get('width') * $this->request->get('height') / 1000000;
        if ($square < 1) {
            $this->squareCoefficient = 1;
        } else {
            $this->squareCoefficient = $square;
        }
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price) {
        // TODO: Implement setPrice() method.
    }

    public function getOptions(): array {
        // TODO: Implement getOptions() method.
        return [];
    }
}
