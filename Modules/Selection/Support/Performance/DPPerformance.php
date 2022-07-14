<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\PumpPerformanceAsPoints;
use Modules\Selection\Tests\Unit\DPPerformanceTest;

/**
 * Double pump performance.
 *
 * @see DPPerformanceTest
 */
final class DPPerformance implements PumpPerformance
{
    private Pump $pump;

    private array $performances = [];

    /**
     * Ctor.
     */
    public function __construct(Pump $pump)
    {
        $this->pump = $pump;
    }

    /**
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        return $this->performances[$position]
            ?? $this->performances[$position] = (new PumpPerformanceAsPoints(
                match ($position) {
                    1 => $this->pump->dp_standby_performance,
                    default => $this->pump->dp_peak_performance
                }
            ))->asArray();
    }
}
