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
     * @param float $flow
     * @param float $head
     * @param float $to
     * @param float $dist
     */
    public function __construct(
        private float $flow,
        private float $head,
        private float $to,
        private float $dist = 1)
    {
    }

    /**
     * @return array
     * @todo need test
     */
    public function asArray(): array
    {
        $systemPerformance = [];
        $y = 0;
        for ($q = 0.1; $y < $this->to; $q += $this->dist) {
            $y = $this->head / ($this->flow * $this->flow) * $q * $q;
            $systemPerformance[] = [
                'x' => $q,
                'y' => $y
            ];
        }
        return $systemPerformance;
    }
}
