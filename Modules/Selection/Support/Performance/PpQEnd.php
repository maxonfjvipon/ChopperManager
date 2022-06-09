<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Last flow value from pump performance.
 */
final class PpQEnd implements Numerable
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
        return ($perfAsArray = $this->origin->asArrayAt($this->position))[count($perfAsArray) - 1][0];

    }
}
