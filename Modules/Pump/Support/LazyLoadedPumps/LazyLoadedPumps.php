<?php

namespace Modules\Pump\Support\LazyLoadedPumps;

use Illuminate\Database\Eloquent\Collection;

/**
 * Lazy loaded pumps.
 */
interface LazyLoadedPumps
{
    /**
     * @return Collection
     */
    public function lazyLoaded(): Collection;
}
