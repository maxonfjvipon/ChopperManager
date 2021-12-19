<?php


namespace Modules\Selection\Support;


class SSystemPerformance
{
    private $flow, $head;

    public function __construct($flow, $head)
    {
        $this->flow = $flow;
        $this->head = $head;
    }

    public function asXYArrayData($to, $dist = 1): array
    {
        $systemPerformance = [];
        $y = 0;
        for ($q = 0.1; $y < $to; $q += $dist) {
            $y = $this->head / ($this->flow * $this->flow) * $q * $q;
            $systemPerformance[] = [
                'x' => $q,
                'y' => $y
            ];
        }
        return $systemPerformance;
    }
}
