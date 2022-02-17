<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;

/**
 * Single pump performance.
 */
final class SPPerformance implements PumpPerformance
{
    /**
     * @var Pump $pump
     */
    private Pump $pump;

    /**
     * @var array $cache
     */
    private array $cache = [];

    /**
     * Ctor wrap.
     * @param Pump $pump
     * @return SPPerformance
     */
    public static function new(Pump $pump)
    {
        return new self($pump);
    }

    /**
     * Ctor.
     * @param Pump $pump
     */
    public function __construct(Pump $pump)
    {
        $this->pump = $pump;
    }

    /**
     * Return array of performance dots. Array looks like:
     * [
     *   0 => [0 => q1, 1 => h1],
     *   1 => [0 => q2, 1 => h2],
     *   ...
     * ]
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        if (!array_key_exists($position, $this->cache)) {
            if (!array_key_exists(1, $this->cache)) {
                $perfAsArr = ArrMapped::new(
                    ArrExploded::new(
                        " ",
                        $this->pump->sp_performance
                    ),
                    fn($value) => floatval($value)
                )->asArray();
                $arr = [];
                for ($i = 0; $i < count($perfAsArr) - 1; $i += 2) {
                    $arr[] = [$perfAsArr[$i], $perfAsArr[$i + 1]];
                }
                $this->cache[1] = $arr;
            }
            $this->cache[$position] = ArrMapped::new(
                $this->cache[1],
                fn (array $dot) => [$dot[0] * $position, $dot[1]]
            )->asArray();
        }
        return $this->cache[$position];
    }
}
