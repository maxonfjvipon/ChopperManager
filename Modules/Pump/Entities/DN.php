<?php

namespace Modules\Pump\Entities;

/**
 * DN.
 */
final class DN
{
    /**
     * @return int[]
     */
    public static function values(): array
    {
        return [15, 20, 25, 32, 40, 50, 65, 80, 100, 125, 150, 200, 250, 300];
    }
}
