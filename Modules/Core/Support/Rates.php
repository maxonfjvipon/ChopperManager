<?php

namespace Modules\Core\Support;

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
            ->symbols(\Modules\Core\Entities\Currency::all()->pluck('code')->all())
            ->base($base)
            ->amount(1)
            ->round($round)
            ->get();
    }

    public function base(): string
    {
        return $this->base;
    }

    public function rate(string $code)
    {
        return $this->rates[array_key_exists($code, $this->rates) ? $code : $this->base];
    }
}
