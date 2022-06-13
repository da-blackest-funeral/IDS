<?php

    namespace App\Services\Helpers\Interfaces;

    use App\Models\Order;

    interface MeasuringService
    {
        public function calculateMeasuringOptions(int $measuringPrice): void;

        public function getResultOrder(): Order;
    }
