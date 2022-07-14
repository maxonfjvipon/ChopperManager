<?php

namespace Modules\Selection\Support\Performance;

/**
 * Pump performance.
 */
interface PumpPerformance
{
    /**
     * Return dots array for {@position}.
     */
    public function asArrayAt(int $position): array;
}
