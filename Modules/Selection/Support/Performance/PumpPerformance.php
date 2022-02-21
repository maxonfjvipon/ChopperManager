<?php

namespace Modules\Selection\Support\Performance;

/**
 * Pump performance
 */
interface PumpPerformance
{
    public function asArrayAt(int $position): array;
}
