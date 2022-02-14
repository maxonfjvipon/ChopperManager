<?php

namespace App\Support;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Text;
use TypeError;

class FormattedPrice implements Text
{
    /**
     * @var float|int $price
     */
    private float|int $price;

    /**
     * @var int $decimals
     */
    private int $decimals;

    /**
     * Ctor wrap.
     * @param float|int $price
     * @param int $decimals
     * @return FormattedPrice
     */
    public static function new(float|int $price, int $decimals = 1): FormattedPrice
    {
        return new self($price, $decimals);
    }

    /**
     * Ctor.
     * @param float|int $price
     * @param int $decimals
     */
    public function __construct(float|int $price, int $decimals = 1)
    {
        $this->price = $price;
        $this->decimals = $decimals;
    }

    public function asString(): string
    {
        return $this->price === 0
            ? 0
            : number_format($this->price, $this->decimals, ",", " ");
    }
}
