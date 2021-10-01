<?php


namespace App\Support\Selections;


class IntersectionPoint
{
    private static $instance;
    private $coefficients, $head, $flow, $x, $y;

    private function __construct()
    {
    }

    public static function by($coefficients, $flow, $head): IntersectionPoint
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
//        self::$instance->coefficients = $coefficients;
//        self::$instance->head = $head;
//        self::$instance->flow = $flow;
//        self::$instance->calculatedCoefficients();
        self::$instance->calculatePoint($coefficients, $flow, $head);
        return self::$instance;
    }

    private function calculatePoint($coefficients, $flow, $head)
    {
        $z = $head / ($flow * $flow);
        $t = $coefficients->k - $z;
        $d = $coefficients->b * $coefficients->b - 4 * $t * $coefficients->c;
        $sqrtD = sqrt($d);
//        $x1 = (-$coefficients->b + $sqrtD) / (2 * $t);
//        $x2 = (-$coefficients->b - $sqrtD) / (2 * $t);
        $this->x = (-$coefficients->b - $sqrtD) / (2 * $t);
//        $this->x = $x1 > $x2 ? $x1 : $x2;
//        dd($coefficients->k * $x2 * $x2 + $coefficients->b * $x2 + $coefficients->c, $z * $this->x * $this->x);
//        $this->y = $coefficients->k * $x2 * $x2 + $coefficients->b * $x2 + $coefficients->c;
        $this->y = $z * $this->x * $this->x;
//        dd($this->y);
    }

    private function calculatedCoefficients()
    {
        $aCoef = $this->coefficients[0] - ($this->head / ($this->flow * $this->flow));
        $d = $this->coefficients[1] * $this->coefficients[1] - 4 * $aCoef * $this->coefficients[2];
        $x1 = (-$this->coefficients[1] + sqrt($d)) / (2 * $aCoef);
        $x2 = (-$this->coefficients[1] - sqrt($d)) / (2 * $aCoef);
        $x = $x1 > $x2 ? $x1 : $x2;
        $this->x = $x;
        $this->y = $this->coefficients[0] * $x * $x + $this->coefficients[1] * $x + $this->coefficients[2];
    }

    public function x()
    {
        return $this->x;
    }

    public function y() {
        return $this->y;
    }

}
