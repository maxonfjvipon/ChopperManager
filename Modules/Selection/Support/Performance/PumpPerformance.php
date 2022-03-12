<?php

namespace Modules\Selection\Support\Performance;

/**
 * Pump performance
 */
interface PumpPerformance
{
    /**
     * Return dots array for {@position}
     * @param int $position
     * @return array
     */
    public function asArrayAt(int $position): array;
}
