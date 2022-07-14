<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumerableOverloaded;

/**
 * Polynomial equation.
 */
trait CanCalcPolynomialEquation
{
    use NumerableOverloaded;

    /**
     * @throws Exception
     */
    private function polynomialEquationValue(float|int|Numerable $arg, array $coefficients)
    {
        $x = $this->firstNumerableOverloaded($arg);

        return $coefficients[0] * $x * $x + $coefficients[1] * $x + $coefficients[2];
    }
}
