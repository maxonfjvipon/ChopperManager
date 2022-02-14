<?php

use App\Support\FormattedPrice;

if (!function_exists('formatted_price')) {
    /**
     * @throws Exception
     */
    function formatted_price(float|int $price, int $decimals = 2): float|int
    {
        return FormattedPrice::new($price, $decimals)->asString();
    }
}
