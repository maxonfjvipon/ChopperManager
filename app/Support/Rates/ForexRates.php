<?php

namespace App\Support\Rates;

use AmrShawky\LaravelCurrency\Facade\Currency as RateCurrency;
use App\Interfaces\Rates;
use App\Models\Enums\Currency;
use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * Rates from Forex.
 */
final class ForexRates implements Rates
{
    private Currency $base;

    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->base = Currency::fromValue(Currency::RUB);
    }

    /**
     * {@inheritDoc}
     */
    #[Pure]
 public function hasTheSameBaseAs(Currency|int $currency): bool
 {
     return $this->base->is($currency);
 }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        $rates = RateCurrency::rates()
            ->latest()
            ->symbols(Currency::getKeys())
            ->base($this->base->key)
            ->amount(1)
            ->round(5)
            ->get();

        return $rates[array_key_exists($code, $rates) ? $code : $this->base->key];
    }
}
