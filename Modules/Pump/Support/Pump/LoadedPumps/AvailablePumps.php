<?php

namespace Modules\Pump\Support\Pump\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;

/**
 * Available loaded pumps
 */
final class AvailablePumps implements LoadedPumps
{
    /**
     * @var LoadedPumps
     */
    private LoadedPumps $origin;

    /**
     * Ctor.
     * @param LoadedPumps $pumps
     */
    public function __construct(LoadedPumps $pumps)
    {
        $this->origin = $pumps;
    }

    /**
     * @inheritDoc
     */
    public function loaded(): Builder
    {
        return $this->origin->loaded()
            ->availableForCurrentUser();
    }
}
