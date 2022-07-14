<?php

namespace App\Support\Rates;

use App\Interfaces\Rates;
use App\Models\Enums\Currency;
use Exception;

/**
 * Exchange rates.
 */
final class RealRates implements Rates
{
    /**
     * @var bool
     */
    private static bool $fromForex = false;

    /**
     * @var Rates
     */
    private Rates $rates;

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->rates = self::$fromForex
            ? new ForexRates()
            : new CBRRates();
    }

    /**
     * @param  Currency|int  $currency
     * @return bool
     */
    public function hasTheSameBaseAs(Currency|int $currency): bool
    {
        return $this->rates->hasTheSameBaseAs($currency);
    }

    /**
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        return $this->rates->rateFor($code);
    }
}
