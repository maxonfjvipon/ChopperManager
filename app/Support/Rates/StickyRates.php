<?php

namespace App\Support\Rates;

use Exception;
use Modules\Project\Entities\Currency;

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
    public function hasTheSameBaseAs(Currency $currency): bool
    {
        return $this->origin->hasTheSameBaseAs($currency);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function rateFor(string $code): mixed
    {
        if (!array_key_exists($code, $this->cached)) {
            $this->cached[$code] = $this->origin->rateFor($code);
        }
        return $this->cached[$code];
    }
}
