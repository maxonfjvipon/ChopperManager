<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\OverloadedElephant\Overloadable;

/**
 * Array of flow values of pump performance
 */
final class PerformanceQs implements Arrayable
{
    use Overloadable;

    /**
     * Ctor.
     * @param array|PumpPerformance $performance
     * @param int $pos
     */
    public function __construct(private array|PumpPerformance $performance, private int $pos) {}

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMapped(
            $this->overload([$this->performance], [[
                'array',
                PumpPerformance::class => fn(PumpPerformance $perf) => $perf->asArrayAt($this->pos)
            ]])[0],
            fn(array $point) => $point[0]
        ))->asArray();
    }
}
