<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOverloaded;
use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Polynomial regression.
 */
final class EqPolynomial implements Equation
{
    use CalculateRegression;
    use CanCalcPolynomialEquation;
    use ArrayableOverloaded;

    private array $cache = [];

    /**
     * Ctor.
     *
     * @param array|Arrayable $data
     * @param int             $precision
     */
    public function __construct(private array|Arrayable $data, private int $precision = 8)
    {
    }

    /**
     * @throws Exception
     */
    public function y(float|int|Numerable $x): float|int|Numerable
    {
        return $this->polynomialEquationValue($x, $this->asArray());
    }

    /**
     * @throws Exception
     */
    public function asArray(): array
    {
        return $this->cache[0] ??= $this->leastSquaresCoefs($this->firstArrayableOverloaded($this->data), 2, $this->precision);
    }
}
