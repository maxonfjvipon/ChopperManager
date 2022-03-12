<?php

use App\Support\FormattedPrice;
use JetBrains\PhpStorm\Pure;

if (!function_exists('formatted_price')) {
    /**
     * @throws Exception
     */
    #[Pure] function formatted_price(float|int $price, int $decimals = 2): float|int
    {
        return (new FormattedPrice($price, $decimals))->asString();
    }
}
