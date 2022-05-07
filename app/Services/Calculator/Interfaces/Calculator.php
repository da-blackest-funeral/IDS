<?php

namespace App\Services\Calculator\Interfaces;

use App\Models\ProductInOrder;
use Illuminate\Support\Collection;

interface Calculator
{
    public function calculate();

    public function getPrice(): float;

    public function setPrice(float $price);

    public function getDeliveryPrice(): float;

    public function getOptions(): Collection;

    public function getNeedMeasuring(): bool;

    public function getMeasuringPrice(): float;

    public function getCount(): int;

    public function getInstallersWage();

    public function getProduct();

    public function calculateInstallationSalary(): float|null;

    public function getInstallationPrice();

    public function getInstallation($property = null);

    public function calculateSalaryForCount(int $count, ProductInOrder $productInOrder);

    // todo перенести это в класс productHelper
    public function productNeedInstallation(): bool;
}
