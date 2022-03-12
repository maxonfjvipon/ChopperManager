<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrReversed;

/**
 * Calculate regression.
 */
trait CalculateRegression
{
    /**
     * @throws Exception
     */
    private function leastSquaresCoefs($data, $order, $precision): array
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
        return (new ArrReversed(
            new ArrMapped(
                $this->gaussianElimination($rhs, $k),
                fn($value) => $this->rounded($value, $precision)
            ))
        )->asArray();
    }

    /**
     * @param $input
     * @param $order
     * @return array
     */
    private function gaussianElimination($input, $order): array
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

    /**
     * @param $number
     * @param $precision
     * @return float|int
     */
    private function rounded($number, $precision): float|int
    {
        $factor = pow(10, $precision);
        return round($number * $factor) / $factor;
    }
}
