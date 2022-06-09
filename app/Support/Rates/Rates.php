<?php

namespace App\Support\Rates;


use App\Models\Enums\Currency;

/**
 * Rates.
 */
interface Rates
{
    /**
     * @param Currency|int $currency
     * @return bool
     */
    public function hasTheSameBaseAs(Currency|int $currency): bool;

    /**
     * @param string $code
     * @return mixed
     */
    public function rateFor(string $code): mixed;
}
