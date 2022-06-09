<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\PointsForPos;
use Modules\Selection\Support\PumpPerformanceAsPoints;

/**
 * NPSH performance
 */
final class NPSHPerformance implements PumpPerformance
{
    /**
     * @var array $cache
     */
    private array $cache = [];

    /**
     * Ctor.
     * @param Pump $pump
     */
    public function __construct(private Pump $pump) {}

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        return $this->cache[$position]
        ?? $this->cache[$position] = (new PointsForPos(
            $this->cache[1]
            ?? $this->cache[1] = (new PumpPerformanceAsPoints(
                $this->pump->NPSH_performance
            ))->asArray(),
            $position
        ))->asArray();
    }
}
