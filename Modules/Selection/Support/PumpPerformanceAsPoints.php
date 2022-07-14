<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrRange;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;

/**
 * Pump performance as points array.
 */
final class PumpPerformanceAsPoints extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(private string $performance)
    {
        parent::__construct(
            new ArrMapped(
                new ArrRange(
                    1,
                    new LengthOf(
                        $perf = array_map(
                            fn ($value) => floatval($value),
                            explode(
                                ' ',
                                $this->performance
                            ),
                        )
                    ),
                    2
                ),
                fn (int $index) => [
                    $perf[$index - 1],
                    $perf[$index],
                ]
            )
        );
    }
}
