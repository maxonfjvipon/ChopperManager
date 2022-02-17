<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\OverloadedElephant\Overloadable;

/**
 * Polynomial equation
 */
trait CanCalcPolynomialEquation
{
    use Overloadable;

    /**
     * @throws Exception
     */
    private function polynomialEquationValue(float|int|Numerable $arg, array $coefficients)
    {
        $x = $this->overload([$arg], [[
            'double',
            'integer',
            Numerable::class => fn (Numerable $num) => $num->asNumber()
        ]])[0];
        return $coefficients[0] * $arg * $arg + $coefficients[1] * $x + $coefficients[2];
    }
}
