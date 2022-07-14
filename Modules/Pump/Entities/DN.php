<?php

namespace Modules\Pump\Entities;

use JetBrains\PhpStorm\Pure;

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

    #[Pure]
 public static function minDNforPump(Pump $pump): int
 {
     return self::values()[array_search($pump->dn_suction, self::values()) + 1];
 }
}
