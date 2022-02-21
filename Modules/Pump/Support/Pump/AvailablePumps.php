<?php

namespace Modules\Pump\Support\Pump;

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
     * Ctor wrap.
     * @param LoadedPumps $pumps
     * @return AvailablePumps
     */
    public static function new(LoadedPumps $pumps): AvailablePumps
    {
        return new self($pumps);
    }

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
