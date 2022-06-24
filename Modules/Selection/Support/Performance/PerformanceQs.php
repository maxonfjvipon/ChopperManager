<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\OverloadedElephant\Overloadable;

/**
 * Array of flow values of pump performance
 */
final class PerformanceQs extends ArrEnvelope
{
    use Overloadable;

    /**
     * Ctor.
     * @param array|PumpPerformance $performance
     * @param int $pos
     * @throws Exception
     */
    public function __construct(private array|PumpPerformance $performance, private int $pos)
    {
        parent::__construct(
            new ArrMapped(
                $this->overload([$this->performance], [[
                    'array',
                    PumpPerformance::class => fn(PumpPerformance $perf) => $perf->asArrayAt($this->pos)
                ]])[0],
                fn(array $point) => $point[0]
            )
        );
    }
}
