<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Last flow value from pump performance.
 */
final class PpQEnd implements Numerable
{
    /**
     * @var PumpPerformance $origin
     */
    private PumpPerformance $origin;

    /**
     * @var int
     */
    private int $position;

    /**
     * @param PumpPerformance $performance
     * @param int $pos
     */
    public function __construct(PumpPerformance $performance, int $pos)
    {
        $this->origin = $performance;
        $this->position = $pos;
    }

    /**
     * @inheritDoc
     */
    public function asNumber(): float|int
    {
        $perfAsArray = $this->origin->asArrayAt($this->position);
        return $perfAsArray[count($perfAsArray) - 1][0];

    }
}
