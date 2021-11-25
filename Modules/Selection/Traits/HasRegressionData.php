<?php

namespace Modules\Selection\Traits;

trait HasRegressionData
{
    abstract protected static function calc($data, $order, $precision);

    private static function gaussianElimination($input, $order): array
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

    private static function round($number, $precision): float|int
    {
        $factor = pow(10, $precision);
        return round($number * $factor) / $factor;
    }
}
