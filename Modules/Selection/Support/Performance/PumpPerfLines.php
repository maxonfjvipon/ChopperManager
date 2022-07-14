<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrRange;
use Modules\Pump\Entities\Pump;

/**
 * Pump performance lines.
 */
final class PumpPerfLines extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param Pump $pump
     * @param int  $count
     */
    public function __construct(private Pump $pump, private int $count = 1)
    {
        parent::__construct(
            new ArrMapped(
                new ArrRange(1, $this->count),
                fn ($num) => new PumpPerfLine($this->pump, $num),
                true
            )
        );
    }
}
