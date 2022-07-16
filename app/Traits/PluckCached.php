<?php

namespace App\Traits;

use Exception;

/**
 * Get ::allOrCached->pluck('id')->all().
 */
trait PluckCached
{
    /**
     * @throws Exception
     */
    public static function pluckFromCached(string $value = 'id', ?string $key = null): array
    {
        return self::allOrCached()->pluck($value, $key)->all();
    }
}
