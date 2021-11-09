<?php


namespace Modules\Selection\Support;


class SystemPerformance
{
    private static $instance;
    private IntersectionPoint $intersectionPoint;
    private $flow, $head;

    private function __construct()
    {
    }

    public static function by($intersectionPoint, $flow, $head)
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        self::$instance->intersectionPoint = $intersectionPoint;
        self::$instance->flow = $flow;
        self::$instance->head = $head;
        return self::$instance;
    }

    public function asLineData(): array
    {
        $systemPerformance = [];
        $y = 0;
        $dist = $this->intersectionPoint->x() < 50 ? 0.5 : 1;
        for ($q = 0.1; $y < $this->intersectionPoint->y(); $q += $dist) {
            $y = round($this->head / ($this->flow * $this->flow) * $q * $q, 1); // to fixed 1
            $systemPerformance[] = [
                'x' => round($q, 1), // to fixed 1
                'y' => $y
            ];
        }
        return $systemPerformance;
    }
}
