<?php


namespace Modules\Selection\Support;

use Modules\Selection\Traits\HasRegressionData;

abstract class RRegression
{
    protected array $coefficients;

    public static function fromCoefficients(array $coefficients)
    {
        return new static($coefficients);
    }

    public static function fromPumpCoefficients($pumpCoefficients)
    {
        return self::fromCoefficients([$pumpCoefficients->k, $pumpCoefficients->b, $pumpCoefficients->c]);
    }

    protected function __construct($coefficients)
    {
        $this->coefficients = $coefficients;
    }

    abstract protected function calculatedY($x);

    public function coefficients(): array
    {
        return $this->coefficients;
    }
}
