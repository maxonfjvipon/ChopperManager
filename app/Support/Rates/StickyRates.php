<?php

namespace App\Support\Rates;

use App\Interfaces\Rates;
use App\Models\Enums\Currency;
use Exception;

/**
 * Exchange rates with caching.
 */
final class StickyRates implements Rates
{
    /**
     * @var Rates $origin
     */
    private Rates $origin;

    /**
     * @var array $cached
     */
    private array $cached = [];

    /**
     * Ctor.
     * @param Rates $origin
     */
    public function __construct(Rates $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @inheritDoc
     */
    public function hasTheSameBaseAs(Currency|int $currency): bool
    {
        return $this->origin->hasTheSameBaseAs($currency);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        return $this->cached[$code] ??= $this->cached[$code] = $this->origin->rateFor($code) ;
    }
}
