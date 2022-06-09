<?php

namespace Modules\Pump\Support\LazyLoadedPumps;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class CachedPumps implements LazyLoadedPumps
{
    /**
     * @var string $key
     */
    private string $key;

    /**
     * @var LazyLoadedPumps $origin
     */
    private LazyLoadedPumps $origin;

    /**
     * Ctor.
     * @param LazyLoadedPumps $pumps
     * @param string $cacheKey
     */
    public function __construct(LazyLoadedPumps $pumps, string $cacheKey)
    {
        $this->origin = $pumps;
        $this->key = $cacheKey;
    }

    /**
     * @inheritDoc
     */
    public function lazyLoaded(): Collection
    {
        if (Cache::has($this->key)) {
            return Cache::get($this->key);
        }
        $pumps = $this->origin->lazyLoaded();
        Cache::put($this->key, $pumps);
        return $pumps;
    }
}
