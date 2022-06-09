<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\PointsForPos;
use Modules\Selection\Support\PumpPerformanceAsPoints;
use Modules\Selection\Tests\Unit\SPPerformanceTest;

/**
 * Single pump performance.
 * @see SPPerformanceTest
 */
final class SPPerformance implements PumpPerformance
{
    /**
     * @var array $cache
     */
    private array $cache = [];

    /**
     * Ctor.
     * @param Pump $pump
     */
    public function __construct(private Pump $pump)
    {
    }

    /**
     * Return array of performance dots. Array looks like:
     * [
     *   0 => [0 => q1, 1 => h1],
     *   1 => [0 => q2, 1 => h2],
     *   ...
     * ]
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        return $this->cache[$position] ??= (new PointsForPos(
            $this->cache[1] ??= new ArrSticky(new PumpPerformanceAsPoints($this->pump->performance)),
            $position
        ))->asArray();
    }
}
