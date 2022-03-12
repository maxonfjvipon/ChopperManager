<?php

namespace Modules\Selection\Support\Regression;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\OverloadedElephant\Overloadable;

/**
 * Polynomial regression
 */
final class EqPolynomial implements Equation
{
    use CalculateRegression, Overloadable, CanCalcPolynomialEquation;

    /**
     * @var array|Arrayable $data
     */
    private array|Arrayable $data;

    /**
     * @var int $precision
     */
    private int $precision;

    /**
     * @param array|Arrayable $data
     * @param int $precision
     */
    public function __construct(array|Arrayable $data, int $precision = 8)
    {
        $this->data = $data;
        $this->precision = $precision;
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
        return $this->leastSquaresCoefs($this->overload([$this->data], [[
            'array',
            Arrayable::class => fn(Arrayable $arr) => $arr->asArray()
        ]])[0], 2, $this->precision);
    }
}
