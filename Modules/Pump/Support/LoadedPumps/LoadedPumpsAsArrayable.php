<?php

namespace Modules\Pump\Support\LoadedPumps;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\OverloadedElephant\Overloadable;
use Modules\Pump\Support\LazyLoadedPumps\LazyLoadedPumps;

/**
 * Loaded pumps as arrayable
 */
final class LoadedPumpsAsArrayable implements Arrayable
{
    use Overloadable;

    /**
     * @var LoadedPumps|LazyLoadedPumps $origin;
     */
    private LoadedPumps|LazyLoadedPumps $origin;

    /**
     * Ctor.
     * @param LoadedPumps|LazyLoadedPumps $pumps
     */
    public function __construct(LoadedPumps|LazyLoadedPumps $pumps)
    {
        $this->origin = $pumps;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return ($this->overload([$this->origin], [[
            LoadedPumps::class      => fn(LoadedPumps $pumps) => $pumps->loaded()->get(),
            LazyLoadedPumps::class  => fn(LazyLoadedPumps $pumps) => $pumps->lazyLoaded()
        ]])[0])->all();
    }
}
