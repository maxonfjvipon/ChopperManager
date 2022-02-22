<?php

namespace Modules\Pump\Support\Pump\LazyLoadedPumps;

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
