<?php


namespace App\Support\Selections;


class IntersectionPoint
{
    private $coefficients, $head, $flow, $point;
    private $isCalculated = false;

    public function __construct($coefficients, $head, $flow)
    {
        $this->coefficients = $coefficients;
        $this->head = $head;
        $this->flow = $flow;
    }

    private function calculatedCoefficients()
    {
        $aCoef = $this->coefficients[0] - ($this->head / ($this->flow * $this->flow));
        $d = $this->coefficients[1] * $this->coefficients[1] - 4 * $aCoef * $this->coefficients[2];
        $x1 = (-$this->coefficients[1] + sqrt($d)) / (2 * $aCoef);
        $x2 = (-$this->coefficients[1] - sqrt($d)) / (2 * $aCoef);
        $x = $x1 > $x2 ? $x1 : $x2;
        $this->point = new Point($x, $this->coefficients[0] * $x * $x + $this->coefficients[1] * $x + $this->coefficients[2]);
        $this->isCalculated = true;
    }

    private function checkCalculation() {
        if (!$this->isCalculated) {
            $this->calculatedCoefficients();
        }
    }

    public function x()
    {
        $this->checkCalculation();
        return $this->point->x();
    }

    public function y() {
        $this->checkCalculation();
        return $this->point->y();
    }

}
