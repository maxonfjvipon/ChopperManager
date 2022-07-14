<?php

namespace App\Interfaces;

use App\Models\Enums\Currency;

/**
 * Rates.
 */
interface Rates
{
    public function hasTheSameBaseAs(Currency|int $currency): bool;

    public function rateFor(string $code): mixed;
}
