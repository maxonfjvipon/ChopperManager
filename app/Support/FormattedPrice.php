<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Text;
use Tests\Unit\FormattedPriceTest;

/**
 * Formatted price for Russian standards.
 *
 * @see FormattedPriceTest
 */
final class FormattedPrice implements Text
{
    private float|int $price;

    private int $decimals;

    /**
     * Ctor.
     */
    public function __construct(float|int $price, int $decimals = 2)
    {
        $this->price = $price;
        $this->decimals = $decimals;
    }

    public function asString(): string
    {
        return 0 === $this->price
            ? 0
            : number_format($this->price, $this->decimals, ',', ' ');
    }
}
