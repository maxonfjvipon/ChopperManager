<?php

namespace Modules\Selection\Support\Performance;

/**
 * Pump performance envelope.
 */
class PPEnvelope implements PumpPerformance
{
    /**
     * Ctor.
     * @param PumpPerformance $performance
     */
    public function __construct(private PumpPerformance $performance)
    {
    }

    /**
     * @inheritDoc
     */
    public function asArrayAt(int $position): array
    {
        return $this->performance->asArrayAt($position);
    }
}
