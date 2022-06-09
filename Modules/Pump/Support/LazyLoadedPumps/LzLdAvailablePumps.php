<?php

namespace Modules\Pump\Support\LazyLoadedPumps;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

final class LzLdAvailablePumps implements LazyLoadedPumps
{
    /**
     * @var LazyLoadedPumps $origin
     */
    private LazyLoadedPumps $origin;

    /**
     * Ctor.
     * @param LazyLoadedPumps $pumps
     */
    public function __construct(LazyLoadedPumps $pumps)
    {
        $this->origin = $pumps;
    }

    /**
     * @inheritDoc
     */
    public function lazyLoaded(): Collection
    {
        return $this->origin->lazyLoaded()
            ->whereIn('series_id', Auth::user()->available_series()->pluck('id')->all());
    }
}
