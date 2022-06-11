<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Pump performance as points array
 */
final class PumpPerformanceAsPoints implements Arrayable
{
    /**
     * Ctor.
     * @param string $performance
     */
    public function __construct(private string $performance) {}

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $perfAsArr = (new ArrMapped(
            new ArrExploded(
                " ",
                $this->performance
            ),
            fn($value) => floatval($value)
        ))->asArray();
        $arr = [];
        for ($i = 0; $i < count($perfAsArr) - 1; $i += 2) {
            $arr[] = [$perfAsArr[$i], $perfAsArr[$i + 1]];
        }
        return $arr;
    }
}