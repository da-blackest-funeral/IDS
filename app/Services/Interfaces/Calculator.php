<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface Calculator
{
    public function calculate(): void;

    public function getPrice(): float;

    public function setPrice(float $price);

    public function getOptions(): Collection;
}
