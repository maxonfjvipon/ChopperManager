<?php

namespace Modules\Selection\Support\Regression;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Numerable;

/**
 * Regression.
 */
interface Equation extends Arrayable
{
    /**
     * Calculate y depends on {@x}.
     */
    public function y(float|int|Numerable $x): float|int|Numerable;
}
