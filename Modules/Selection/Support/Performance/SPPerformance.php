<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrTernary;
use Maxonfjvipon\Elegant_Elephant\Logical\KeyExists;
use Maxonfjvipon\Elegant_Elephant\Logical\Negation;
use Modules\Pump\Entities\Pump;
use PhpParser\Node\Expr\New_;

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
        return $this->cache[$position] ?? $this->cache[$position] = (new ArrMapped(
                $this->cache[1] ?? $this->cache[1] = (function () {
                    $perfAsArr = (new ArrMapped(
                        new ArrExploded(
                            " ",
                            $this->pump->sp_performance
                        ),
                        fn($value) => floatval($value)
                    ))->asArray();
                    $arr = [];
                    for ($i = 0; $i < count($perfAsArr) - 1; $i += 2) {
                        $arr[] = [$perfAsArr[$i], $perfAsArr[$i + 1]];
                    }
                    return $arr;
                })(),
                fn(array $dot) => [$dot[0] * $position, $dot[1]]
            ))->asArray();
    }
}
