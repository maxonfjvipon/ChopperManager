<?php

namespace Modules\Core\Support;

use AmrShawky\LaravelCurrency\Facade\Currency;
use Illuminate\Support\Facades\Auth;

/**
 * Rates.
 * @package Modules\Core\Support
 */
class Rates
{
    /**
     * @var $rates
     */
    private $rates;

    /**
     * @var string $base
     */
    private string $base;

    public function __construct($round = 5)
    {
        $this->base = Auth::user()->currency->code;
        $this->rates = Currency::rates()
            ->latest()
            ->symbols(\Modules\Core\Entities\Currency::all()->pluck('code')->all())
            ->base($this->base)
            ->amount(1)
            ->round($round)
            ->get();
    }

    /**
     * @return string
     */
    public function base(): string
    {
        return $this->base;
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function rate(string $code): mixed
    {
        return $this->rates[array_key_exists($code, $this->rates) ? $code : $this->base];
    }
}
