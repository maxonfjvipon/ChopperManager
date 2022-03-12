<?php

namespace App\Support\Rates;

use Exception;
use Modules\Project\Entities\Currency;

/**
 * Exchange rates.
 * @package Modules\Project\Support
 */
final class RealRates implements Rates
{
    /**
     * @var bool $fromForex
     */
    private static bool $fromForex = true;

    /**
     * @var Rates $rates
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
     * @param Currency $currency
     * @return bool
     */
    public function hasTheSameBaseAs(Currency $currency): bool
    {
        return $this->rates->hasTheSameBaseAs($currency);
    }

    /**
     * @param string $code
     * @return mixed
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        return $this->rates->rateFor($code);
    }
}
