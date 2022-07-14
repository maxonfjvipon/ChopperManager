<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\PumpPerformanceAsPoints;

/**
 * Shaft power pump performance.
 */
final class ShPPerformance implements PumpPerformance
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
            ?? $this->cache[$position] = (new ArrMapped(
                $this->cache[1]
                ?? $this->cache[1] = (new PumpPerformanceAsPoints(
                    $this->pump->ShP_performance
                ))->asArray(),
                fn (array $point) => [$point[0] * $position, $point[1] * $position]
            ))->asArray();
    }
}
