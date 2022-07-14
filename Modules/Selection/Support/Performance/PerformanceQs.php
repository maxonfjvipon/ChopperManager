<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Array of flow values of pump performance.
 */
final class PerformanceQs extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param array|PumpPerformance $performance
     * @param int                   $pos
     *
     * @throws Exception
     */
    public function __construct(private array|PumpPerformance $performance, private int $pos)
    {
        parent::__construct(
            new ArrMapped(
                is_array($this->performance)
                    ? $this->performance
                    : $this->performance->asArrayAt($this->pos),
                fn (array $point) => $point[0]
            )
        );
    }
}
