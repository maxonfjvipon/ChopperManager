<?php

namespace Modules\Core\Support;

use AmrShawky\LaravelCurrency\Facade\Currency;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Rates.
 * @package Modules\Core\Support
 */
final class Rates
{
    /**
     * @var $rates
     */
    private $rates;

    /**
     * @var string $base
     */
    private string $base;

    /**
     * Ctor wrap.
     * @param int $round
     * @return Rates
     */
    public static function new($round = 5): Rates
    {
        return new self($round);
    }

    /**
     * Ctor.
     * @param $round
     * @throws Exception
     */
    private function __construct($round)
    {
        $this->base = \Modules\Core\Entities\Currency::allOrCached()->find(Auth::user()->currency_id)->code;
        $this->rates = Currency::rates()
            ->latest()
            ->symbols(\Modules\Core\Entities\Currency::allOrCached()->pluck('code')->all())
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
