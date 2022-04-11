<?php

namespace App\Services\Calculator\Classes;

trait HasSquare
{
    protected float $squareCoefficient = 1.0;

    protected function setSquareCoefficient() {
        $square = $this->request->get('width') * $this->request->get('height') / 1000000;
        if ($square < 1) {
            $this->squareCoefficient = 1;
        } else {
            $this->squareCoefficient = $square;
        }
    }
}
