<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOverloaded;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\OverloadedElephant\Overloadable;

/**
 * Polynomial regression
 */
final class EqPolynomial implements Equation
{
    use CalculateRegression, Overloadable, CanCalcPolynomialEquation, ArrayableOverloaded;

    /**
     * @var array $cache
     */
    private array $cache = [];

    /**
     * Ctor.
     * @param array|Arrayable $data
     * @param int $precision
     */
    public function __construct(private array|Arrayable $data, private int $precision = 8)
    {
    }

    /**
     * @param float|int|Numerable $x
     * @return float|int|Numerable
     * @throws Exception
     */
    public function y(float|int|Numerable $x): float|int|Numerable
    {
        return $this->polynomialEquationValue($x, $this->asArray());
    }

    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        return $this->cache[0] ??= $this->leastSquaresCoefs($this->firstArrayableOverloaded($this->data), 2, $this->precision);
    }
}
