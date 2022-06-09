<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Traits\CalcPerformanceLines;

/**
 * Pump performance line
 */
final class PumpPerfLine implements Arrayable
{
    use CalcPerformanceLines;

    /**
     * Ctor.
     * @param Pump $pump
     * @param int $position
     */
    public function __construct(private Pump $pump, private int $position = 1) {}

    /**
     * @throws Exception
     */
    public function asArray(): array
    {
        return $this->calcLine(
            (new PerformanceQs(
                $this->pump->performance(),
                $this->position
            ))->asArray(),
            new EqFromPumpCoefficients(
                $this->pump->coefficientsAt($this->position)
            ),
            $this->position
        );
    }
}
