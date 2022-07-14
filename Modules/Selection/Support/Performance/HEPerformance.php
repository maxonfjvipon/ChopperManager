<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\PointsForPos;
use Modules\Selection\Support\PumpPerformanceAsPoints;

/**
 * Hydraulic efficiency performance as array for position.
 */
final class HEPerformance implements PumpPerformance
{
    private array $cache = [];

    /**
     * Ctor.
     */
    public function __construct(private Pump $pump)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        return $this->cache[$position]
            ?? $this->cache[$position] = (new PointsForPos(
                $this->cache[1]
                ?? $this->cache[1] = (new PumpPerformanceAsPoints($this->pump->HE_performance))->asArray(),
                $position
            ))->asArray();
    }
}
