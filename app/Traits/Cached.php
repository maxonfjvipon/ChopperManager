<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Cached model.
 */
trait Cached
{
    /**
     * @return string cache key
     *
     * @throws Exception
     */
    protected static function getCacheKey(): string
    {
        throw new Exception('getCacheKey method must be overridden');
    }

    /**
     * @throws Exception
     */
    public static function allOrCached(): Collection|array
    {
        if (Cache::has(self::getCacheKey())) {
            return Cache::get(self::getCacheKey());
        }
        $all = self::all();
        Cache::put(self::getCacheKey(), $all);

        return $all;
    }

    /**
     * @throws Exception
     */
    public static function clearCache()
    {
        Cache::forget(self::getCacheKey());
    }
}
