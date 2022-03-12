<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;

/**
 * Double pump performance.
 */
final class DPPerformance implements PumpPerformance
{
    /**
     * @var Pump $pump
     */
    private Pump $pump;

    /**
     * @var array $performances
     */
    private array $performances = [];

    public function __construct(Pump $pump)
    {
        $this->pump = $pump;
    }

    /**
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        return $this->performances[$position] ?? $this->performances[$position] = (function () use ($position) {
            $perfAsArr = (new ArrMapped(
                new ArrExploded(
                    " ",
                    match ($position) {
                        1 => $this->pump->dp_standby_performance,
                        default => $this->pump->dp_peak_performance
                    }
                ),
                fn($value) => floatval($value)
            ))->asArray();
            $arr = [];
            for ($i = 0; $i < count($perfAsArr) - 1; $i += 2) {
                $arr[] = [$perfAsArr[$i], $perfAsArr[$i + 1]];
            }
            return $arr;
        })();
    }
}
