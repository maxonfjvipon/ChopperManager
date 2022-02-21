<?php

namespace App\Support\Rates;

use Modules\Project\Entities\Currency;

/**
 * Rates.
 */
interface Rates
{
    /**
     * @param Currency $currency
     * @return bool
     */
    public function hasTheSameBaseAs(Currency $currency): bool;

    /**
     * @param string $code
     * @return mixed
     */
    public function rateFor(string $code): mixed;
}
