<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * System performance.
 */
final class SystemPerformance implements Arrayable
{
    /**
     * Ctor.
     */
    public function __construct(
        private float $flow,
        private float $head,
        private float $to,
        private float $dist = 1
    ) {
    }

    /**
     * @todo need test
     */
    public function asArray(): array
    {
        $systemPerformance = [];
        $y = 0;
        for ($q = 0.1; $y < $this->to; $q += $this->dist) {
            $systemPerformance[] = [
                'x' => $q,
                'y' => $y = $this->head / ($this->flow * $this->flow) * $q * $q,
            ];
        }

        return $systemPerformance;
    }
}
