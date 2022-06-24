<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Flow step.
 */
final class FlowStep implements Numerable
{
    /**
     * @param PerformanceQs $xx
     */
    public function __construct(private Arrayable $xx)
    {
    }

    public function asNumber(): float|int
    {
        $graphicsDist = ($_xx = $this->xx->asArray())[count($_xx) - 1] - $_xx[0];
        foreach ([0.1, 0.5, 1, 2, 5, 10, 20] as $step) {
            if ($graphicsDist <= $step * 50) {
                return $step;
            }
        }
        return 20;
    }
}
