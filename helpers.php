<?php

use App\Support\FormattedPrice;
use Illuminate\Support\Carbon;
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

if (!function_exists('formatted_date')) {
    /**
     * @param Carbon $datetime
     * @param string $format
     * @return string
     */
    function formatted_date(Carbon $datetime, string $format = "d.m.Y H:i"): string
    {
        return date_format($datetime, $format);
    }
}