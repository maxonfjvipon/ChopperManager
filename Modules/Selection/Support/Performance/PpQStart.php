<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Start flow value from pump performance
 */
final class PpQStart implements Numerable
{
    /**
     * Ctor.
     * @param PumpPerformance $origin
     * @param int $position
     */
    public function __construct(private PumpPerformance $origin, private int $position) {}

    /**
     * @inheritDoc
     */
    public function asNumber(): float|int
    {
        return $this->origin->asArrayAt($this->position)[0][0];
    }
}
