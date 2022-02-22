<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\Addition;
use Maxonfjvipon\Elegant_Elephant\Numerable\MaxOf;

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
        $max = (new MaxOf(
            $this->head,
            ...ArrMapped::new(
                $this->performance->asArrayAt(1),
                fn(array $point) => $point[1]
            )->asArray())
        )->asNumber();
        return $max + ($max < 10 ? 3 : 10);
    }
}
