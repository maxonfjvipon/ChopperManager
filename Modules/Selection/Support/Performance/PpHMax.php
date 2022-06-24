<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\MaxOf;

/**
 * Max head of pump performance
 */
final class PpHMax implements Numerable
{
    /**
     * @var float $head
     */
    private float $head;

    /**
     * Ctor.
     * @param PumpPerformance $performance
     * @param float|null $head
     */
    public function __construct(private PumpPerformance $performance, ?float $head = 0)
    {
        $this->head = $head ?? 0;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function asNumber(): float|int
    {
        return ($max = MaxOf::new(
                $this->head,
                ...new ArrMapped(
                    $this->performance->asArrayAt(1),
                    fn(array $point) => $point[1]
                )
            )->asNumber()) + $max * 0.1;
    }
}
