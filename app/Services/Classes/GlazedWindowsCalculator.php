<?php

namespace App\Services\Classes;

use App\Http\Requests\SaveOrderRequest;
use App\Models\GlazedWindows\Additional;
use App\Models\GlazedWindows\GlazedWindows;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GlazedWindowsCalculator extends BaseCalculator
{
    use HasSquare;

    protected Collection $additional;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->additional = Additional::groupBy('name')
            ->get('name')
            ->pluck('name');
    }

    public function calculate(): void {
        parent::calculate();
        $this->calculateGlazedWindowsPrice();
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

    public function getOptions(): Collection {
        return $this->options;
    }
}
