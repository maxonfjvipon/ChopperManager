<?php

namespace Modules\Pump\Support\Pump;

use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * Loaded pumps as arrayable
 */
final class LoadedPumpsAsArrayable implements Arrayable
{
    /**
     * @var LoadedPumps $origin;
     */
    private LoadedPumps $origin;

    /**
     * Ctor wrap.
     * @param LoadedPumps $pumps
     * @return LoadedPumpsAsArrayable
     */
    public static function new(LoadedPumps $pumps): LoadedPumpsAsArrayable
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
    public function asArray(): array
    {
        return $this->origin->loaded()->get()->all();
    }
}
