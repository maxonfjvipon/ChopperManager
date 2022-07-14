<?php

use App\Support\FormattedPrice;
use Carbon\Carbon;
use JetBrains\PhpStorm\Pure;

if (! function_exists('formatted_price')) {
    /**
     * @throws Exception
     */
    #[Pure] function formatted_price(float|int $price, int $decimals = 2): float|int
    {
        return (new FormattedPrice($price, $decimals))->asString();
    }
}

if (! function_exists('formatted_date')) {
    function formatted_date(?Carbon $datetime, string $format = 'd.m.Y H:i'): string
    {
        return $datetime ? date_format($datetime, $format) : '';
    }
}
