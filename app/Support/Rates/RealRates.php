<?php

namespace App\Support\Rates;

use AmrShawky\LaravelCurrency\Facade\Currency as RateCurrency;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Currency;

/**
 * Exchange rates.
 * @package Modules\Core\Support
 */
final class RealRates implements Rates
{
    /**
     * @var int $round
     */
    private int $round;

    /**
     * @var string $base
     */
    private string $base;

    /**
     * Ctor wrap.
     * @param int $round
     * @return RealRates
     * @throws Exception
     */
    public static function new(int $round = 5): RealRates
    {
        return new self($round);
    }

    /**
     * Ctor.
     * @param int $round
     * @throws Exception
     */
    public function __construct(int $round = 5)
    {
        $this->round = $round;
        $this->base = Currency::allOrCached()->find(Auth::user()->currency_id)->code;
    }

    /**
     * @param Currency $currency
     * @return bool
     */
    public function hasTheSameBaseAs(Currency $currency): bool
    {
        return $this->base === $currency->code;
    }

    /**
     * @param string $code
     * @return mixed
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        $rates = RateCurrency::rates()
            ->latest()
            ->symbols(Currency::allOrCached()->pluck('code')->all())
            ->base($this->base)
            ->amount(1)
            ->round($this->round)
            ->get();
        return $rates[array_key_exists($code, $rates) ? $code : $this->base];
    }
}
