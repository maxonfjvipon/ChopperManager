<?php


namespace Modules\Selection\Support;

class Regression
{
    private static $instance;
    private $data;
    private $order;
    private $precision;
    private $coefficients = null;
    private $types = [
        'polynomial' => 'polynomial',
    ];
    private $type = null;

    private function __construct()
    {
    }

    public static function withCoefficients($coefficients): Regression
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        self::$instance->coefficients = $coefficients;
        self::$instance->type = self::$instance->types['polynomial'];
        return self::$instance;
    }

    public static function withData($data, $order = 2, $precision = 7): Regression
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        self::$instance->data = $data;
        self::$instance->order = $order;
        self::$instance->precision = $precision;
        return self::$instance;
    }

    public function polynomial(): Regression
    {
        $lhs = array();
        $rhs = array();
        $a = 0;
        $b = 0;
        $len = count($this->data);
        $k = $this->order + 1;

        for ($i = 0; $i < $k; $i++) {
            for ($l = 0; $l < $len; $l++) {
                if ($this->data[$l][1] != null) {
                    $a += pow($this->data[$l][0], $i) * $this->data[$l][1];
                }
            }

            $lhs[] = $a;
            $a = 0;

            $c = array();
            for ($j = 0; $j < $k; $j++) {
                for ($_l = 0; $_l < $len; $_l++) {
                    if ($this->data[$_l][1] != null) {
                        $b += pow($this->data[$_l][0], $i + $j);
                    }
                }
                $c[] = $b;
                $b = 0;
            }
            $rhs[] = $c;
        }
        $rhs[] = $lhs;

        $this->coefficients = array_reverse(array_map(function ($v) {
            return $this->round($v);
        }, $this->gaussianElimination($rhs, $k)));
        $this->type = $this->types['polynomial'];

        return $this;
    }

    public function coefficients(): array
    {
        if ($this->coefficients == null) {
            return array();
        }
        return $this->coefficients;
    }

    public function calculatedY($x)
    {
        if ($this->type == null) {
            return 0;
        }
        switch ($this->type) {
            case $this->types['polynomial']:
            {
                return $this->coefficients[0] * $x * $x + $this->coefficients[1] * $x + $this->coefficients[2];
            }
            default:
                return 0;
        }
    }

    public function gaussianElimination($input, $order): array
    {
        $matrix = $input;
        $n = count($input) - 1;
        $coefficients = array($order);

        for ($i = 0; $i < $n; $i++) {
            $maxrow = $i;
            for ($j = $i + 1; $j < $n; $j++) {
                if (abs($matrix[$i][$j]) > abs($matrix[$i][$maxrow])) {
                    $maxrow = $j;
                }
            }

            for ($k = $i; $k < $n + 1; $k++) {
                $tmp = $matrix[$k][$i];
                $matrix[$k][$i] = $matrix[$k][$maxrow];
                $matrix[$k][$maxrow] = $tmp;
            }

            for ($_j = $i + 1; $_j < $n; $_j++) {
                for ($_k = $n; $_k >= $i; $_k--) {
                    $matrix[$_k][$_j] -= $matrix[$_k][$i] * $matrix[$i][$_j] / $matrix[$i][$i];
                }
            }
        }

        for ($_j2 = $n - 1; $_j2 >= 0; $_j2--) {
            $total = 0;
            for ($_k2 = $_j2 + 1; $_k2 < $n; $_k2++) {
                $total += $matrix[$_k2][$_j2] * $coefficients[$_k2];
            }

            $coefficients[$_j2] = ($matrix[$n][$_j2] - $total) / $matrix[$_j2][$_j2];
        }
        ksort($coefficients);
        return $coefficients;
    }

    private function round($number)
    {
        $factor = pow(10, $this->precision);
        return round($number * $factor) / $factor;
    }
}
