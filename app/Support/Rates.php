<?php

namespace App\Support;

use AmrShawky\LaravelCurrency\Facade\Currency;


class Rates
{
    private $rates;
    private string $base;

    public function __construct(string $base, int $round = 5)
    {
        $this->base = $base;
        $this->rates = Currency::rates()
            ->latest()
            ->symbols(\App\Models\Currency::all()->pluck('code')->all())
            ->base($base)
            ->amount(1)
            ->round($round)
            ->get();
    }

    public function rate(string $code)
    {
        return $this->rates[array_key_exists($code, $this->rates) ? $code : $this->base];
    }
}