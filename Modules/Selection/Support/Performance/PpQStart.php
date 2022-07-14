<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Any\FirstOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumEnvelope;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumerableOf;

/**
 * Start flow value from pump performance.
 */
final class PpQStart extends NumEnvelope
{
    /**
     * Ctor.
     *
     * @param PumpPerformance $origin
     * @param int             $position
     */
    public function __construct(private PumpPerformance $origin, private int $position)
    {
        parent::__construct(
            new NumerableOf(
                new FirstOf(
                    new ArrayableOf(
                        new FirstOf(
                            $this->origin->asArrayAt($this->position)
                        )
                    )
                )
            )
        );
    }
}
