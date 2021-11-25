<?php

namespace Modules\Selection\Support;

use Modules\Selection\Traits\HasRegressionData;

class PolynomialRegression extends RRegression
{
    use HasRegressionData;

    public static function fromData($data, $order = 2, $precision = 7)
    {
        return new static(self::calc($data, $order, $precision));
    }

    public function calculatedY($x)
    {
        return $this->coefficients[0] * $x * $x + $this->coefficients[1] * $x + $this->coefficients[2];
    }

    protected static function calc($data, $order, $precision): array
    {
        $lhs = array();
        $rhs = array();
        $a = 0;
        $b = 0;
        $len = count($data);
        $k = $order + 1;

        for ($i = 0; $i < $k; $i++) {
            for ($l = 0; $l < $len; $l++) {
                if ($data[$l][1] != null) {
                    $a += pow($data[$l][0], $i) * $data[$l][1];
                }
            }

            $lhs[] = $a;
            $a = 0;

            $c = array();
            for ($j = 0; $j < $k; $j++) {
                for ($_l = 0; $_l < $len; $_l++) {
                    if ($data[$_l][1] != null) {
                        $b += pow($data[$_l][0], $i + $j);
                    }
                }
                $c[] = $b;
                $b = 0;
            }
            $rhs[] = $c;
        }
        $rhs[] = $lhs;

        $coefficients = array_reverse(array_map(function ($v) use ($precision) {
            return self::round($v, $precision);
        }, self::gaussianElimination($rhs, $k)));

        return $coefficients;
    }
}
