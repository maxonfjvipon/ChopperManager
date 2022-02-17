<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Max head of pump performance
 */
final class PpHMax implements Numerable
{
    /**
     * @var PumpPerformance $performance
     */
    private PumpPerformance $performance;

    /**
     * @var float $head
     */
    private float $head;

    /**
     * Ctor.
     * @param PumpPerformance $performance
     * @param float $head
     */
    public function __construct(PumpPerformance $performance, float $head = 0)
    {
        $this->head = $head;
        $this->performance = $performance;
    }

    /**
     * @inheritDoc
     */
    public function asNumber(): float|int
    {
        $max = max($this->head, ...ArrMapped::new(
            $this->performance->asArrayAt(1),
            fn (array $point) => $point[1]
        )->asArray());
        return $max + ($max < 10 ? 3 : 10);
    }
}
