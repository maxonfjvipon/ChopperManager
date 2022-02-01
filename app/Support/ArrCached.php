<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * Cached arrayable.
 * @package App\Support
 */
final class ArrCached implements Arrayable
{
    /**
     * @var Arrayable $origin
     */
    private Arrayable $origin;

    /**
     * @var string $key
     */
    private string $key;

    /**
     * Ctor wrap.
     * @param string $key
     * @param Arrayable $arrayable
     * @return ArrCached
     */
    public static function new(string $key, Arrayable $arrayable): ArrCached
    {
        return new self($key, $arrayable);
    }

    /**
     * Ctor.
     * @param string $key
     * @param Arrayable $arrayable
     */
    private function __construct(string $key, Arrayable $arrayable)
    {
        $this->key = $key;
        $this->origin = $arrayable;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        if (Cache::has($this->key)) {
            return Cache::get($this->key);
        }
        $toCache = $this->origin->asArray();
        Cache::put($this->key, $toCache);
        return $toCache;
    }
}
