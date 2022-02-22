<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Start flow value from pump performance
 */
final class PpQStart implements Numerable
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
        return $this->origin->asArrayAt($this->position)[0][0];
    }
}
