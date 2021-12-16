<?php


namespace Modules\Selection\Support;


use Modules\Pump\Entities\PumpsAndCoefficients;

class PPumpPerformance
{
    private $pump;
    private array $performanceAsArray;

    public function __construct($pump, string $pumpPerformance = null)
    {
        $this->pump = $pump;
        $this->performanceAsArray = array_map(function ($value) {
            return (float)$value;
        }, explode(" ", $pumpPerformance ?: $pump->sp_performance));
    }

    private function pointDist(array $xx): float|int
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

    public function qStart($position): float|int
    {
        return $this->performanceAsArray[0] * $position;
    }

    public function qEnd($position): float|int
    {
        return $this->performanceAsArray[count($this->performanceAsArray) - 2] * $position;
    }

    public function asArrayData($position): array
    {
        $data = [];
        $length = count($this->performanceAsArray);
        for ($i = 0; $i < $length; $i += 2) {
            $data[] = [
                '0' => $this->performanceAsArray[$i] * $position,
                '1' => $this->performanceAsArray[$i + 1]
            ];
        }
        return $data;
    }

    public function asPointArray($position): array
    {
        $data = [];
        $length = count($this->performanceAsArray);
        for ($i = 0; $i < $length; $i += 2) {
            $data[] = new Point($this->performanceAsArray[$i] * $position, $this->performanceAsArray[$i + 1]);
        }
        return $data;
    }

    private function createdCoefficients($position)
    {
        $coefficients = PolynomialRegression::fromData($this->asArrayData($position))->coefficients();
        return PumpsAndCoefficients::create([
            'pump_id' => $this->pump->id,
            'position' => $position,
            'k' => $coefficients[0],
            'b' => $coefficients[1],
            'c' => $coefficients[2],
        ]);
    }

    public function coefficientsForPosition($position)
    {
        $coefficients = $this->pump->coefficients->firstWhere('position', $position);
        return $coefficients ?? $this->createdCoefficients($position);
    }

    public function hMax($head = 0): float
    {
        return max($head, max(array_map(fn(Point $point) => $point->y(), $this->asPointArray(1)))) + 10;
    }

    public function asRegressedLines($pc): array
    {
        $lines = [];
        for ($curPos = 1; $curPos <= $pc; ++$curPos) {
            $lines[] = $this->asRegressedPointArray($curPos);
        }
        return $lines;
    }

    public function asRegressedPointArray($position = 1): array
    {
        $xx = array_map(fn(Point $point) => $point->x(), $this->asPointArray($position));
        $qDist = $this->pointDist($xx);
        $xxLength = count($xx);
        $line = [];
        $regression = PolynomialRegression::fromPumpCoefficients($this->coefficientsForPosition($position));

        for ($x =  $xx[0]; $x <= $xx[$xxLength - 1]; $x += $qDist * $position) {
            $line[] = (new Point(
                $x,
                $regression->calculatedY($x)
//                round($x, 1),
//                round($regression->calculatedY($x), 1)
            ))->asArray();
//            dd($line);
        }
        if ($line[count($line) - 1]['x'] != $xx[$xxLength - 1]) {
            $line[] = (new Point(
                $xx[$xxLength - 1],
                $regression->calculatedY($xx[$xxLength - 1])
//                round($xx[$xxLength - 1], 1),
//                round($regression->calculatedY($xx[$xxLength - 1]), 1)
            ))->asArray();
        }
        return $line;
    }
}
