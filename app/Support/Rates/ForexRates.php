<?php

namespace App\Support\Rates;

use AmrShawky\LaravelCurrency\Facade\Currency as RateCurrency;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Entities\Currency;

/**
 * Rates from Forex
 */
final class ForexRates implements Rates
{
    /**
     * @var string $base
     */
    private string $base;

    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->base = Currency::allOrCached()->find(Auth::user()->currency_id)->code;
    }


    /**
     * @inheritDoc
     */
    public function hasTheSameBaseAs(Currency $currency): bool
    {
        return $this->base === $currency->code;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        $rates = RateCurrency::rates()
            ->latest()
            ->symbols(Currency::allOrCached()->pluck('code')->all())
            ->base($this->base)
            ->amount(1)
            ->round(5)
            ->get();
        return $rates[array_key_exists($code, $rates) ? $code : $this->base];
    }
}
