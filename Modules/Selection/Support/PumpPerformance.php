<?php


namespace Modules\Selection\Support;


class PumpPerformance
{
    private static $instance;
    private string $performance;

    private function dist($xx)
    {
        $graphicsDist = $xx[count($xx) - 1] - $xx[0];
        $dist = 0;
        if ($graphicsDist < 20) {
            $dist = 0.5;
        }
        if ($graphicsDist >= 20 && $graphicsDist < 50) {
            $dist = 1;
        }
        if ($graphicsDist >= 50 && $graphicsDist < 100) {
            $dist = 2;
        }
        if ($graphicsDist >= 100 && $graphicsDist < 200) {
            $dist = 5;
        }
        if ($graphicsDist >= 200 && $graphicsDist < 500) {
            $dist = 10;
        }
        if ($graphicsDist >= 500) {
            $dist = 20;
        }
        return $dist;
    }

    private function __construct()
    {
    }

    public static function by($performance): PumpPerformance
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        self::$instance->performance = $performance;
        return self::$instance;
    }

    public function asArray(): array
    {
        return array_map(function ($value) {
            return (float)$value;
        }, explode(" ", $this->performance));
    }

    public function lineData($position): array
    {
        $data = [];
        $performanceAsArray = $this->asArray();
        for ($i = 0; $i < count($performanceAsArray); $i += 2) {
            $data[] = [
                '0' => $performanceAsArray[$i] * $position,
                '1' => $performanceAsArray[$i + 1]
            ];
        }
        return $data;
    }

    public function asPerformanceLineData($count, Regression $regression): array
    {
        $xx = array_map(function ($performanceItem) {
            return $performanceItem[0];
        }, $this->lineData($count));
        $dist = $this->dist($xx);
        $yMax = 0;
        $line = array();
        for ($x = $xx[0]; $x <= $xx[count($xx) - 1]; $x += $dist * $count) {
            $y = $regression->calculatedY($x);
            if ($y > $yMax) {
                $yMax = $y;
            }
            $line[] = [
                'x' => round($x, 1),
                'y' => round($y, 1)
            ];
        }
        if ($line[count($line) - 1]['x'] != $xx[count($xx) - 1]) {
            $y = $regression->calculatedY($xx[count($xx) - 1]);
            $line[] = [
                'x' => round($xx[count($xx) - 1], 1),
                'y' => round($y, 1)
            ];
        }
        return [
            'line' => $line,
            'yMax' => $yMax + 10
        ];
    }

}
