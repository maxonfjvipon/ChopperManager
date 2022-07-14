<?php

namespace Modules\Selection\Traits;

/**
 * Axis step for graphics.
 */
trait AxisStep
{
    public function axisStep($maxValue): float|int
    {
        $steps = [0.01, 0.02, 0.05, 0.1, 0.2, 0.5, 1, 2, 5, 10, 15, 20, 35, 50, 75, 100, 150, 200, 350, 500, 750, 1000];
        foreach ($steps as $step) {
            if ($maxValue <= $step * 7) {
                return $step;
            }
        }

        return 2000;
    }
}
