<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Modules\Pump\Entities\PumpCoefficients;

/**
 * Equation from {@see PumpCoefficients}.
 */
final class EqFromPumpCoefficients implements Equation
{
    use CanCalcPolynomialEquation;

    public function __construct(private PumpCoefficients $coefficients)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function y(float|int|Numerable $x): float|int|Numerable
    {
        return $this->polynomialEquationValue($x, $this->asArray());
    }

    public function asArray(): array
    {
        return [$this->coefficients->k, $this->coefficients->b, $this->coefficients->c];
    }
}
