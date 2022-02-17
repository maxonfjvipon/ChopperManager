<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * System performance.
 */
final class SystemPerformance implements Arrayable
{
    /**
     * @var float $flow
     */
    private float $flow;

    /**
     * @var float $head
     */
    private float $head;

    /**
     * @var float $to
     */
    private float $to;

    /**
     * @var float $dist
     */
    private float $dist;

    public function __construct($flow, $head, $to, $dist = 1)
    {
        $this->flow = $flow;
        $this->head = $head;
        $this->to = $to;
        $this->dist = $dist;
    }

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
