<?php

namespace Modules\Pump\Support\Pump\LazyLoadedPumps;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

final class LzLdAvailablePumps implements LazyLoadedPumps
{
    /**
     * @var LazyLoadedPumps $origin
     */
    private LazyLoadedPumps $origin;

    /**
     * Ctor wrap.
     * @param LazyLoadedPumps $pumps
     * @return LazyLoadedPumps
     */
    public static function new(LazyLoadedPumps $pumps): LazyLoadedPumps
    {
        return new self($pumps);
    }

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
            ->whereIn('id', Auth::user()->available_pumps()->pluck('pumps.id')->all());
    }
}
