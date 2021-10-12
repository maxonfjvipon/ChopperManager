<?php


namespace App\Support\Selections;


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
        for ($q = 0.2; $y < $this->intersectionPoint->y(); $q += 1) {
            $y = round($this->head / ($this->flow * $this->flow) * $q * $q, 1); // to fixed 1
            $systemPerformance[] = [
                'x' => round($q, 1), // to fixed 1
                'y' => $y
            ];
        }
        return $systemPerformance;
    }
}
