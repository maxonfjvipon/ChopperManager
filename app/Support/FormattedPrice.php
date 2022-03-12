<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Text;
use Tests\Unit\FormattedPriceTest;

/**
 * Formatted price for Russian standards
 * @see FormattedPriceTest
 */
final class FormattedPrice implements Text
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
     * Ctor.
     * @param float|int $price
     * @param int $decimals
     */
    public function __construct(float|int $price, int $decimals = 2)
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
