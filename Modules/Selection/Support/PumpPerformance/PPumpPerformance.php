<?php


namespace Modules\Selection\Support\PumpPerformance;

use JetBrains\PhpStorm\Pure;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpsAndCoefficients;
use Modules\Selection\Support\Point;
use Modules\Selection\Support\PolynomialRegression;

class PPumpPerformance
{
    protected $pump;
    protected array $performanceAsArray;

    protected function __construct($pump, $performance)
    {
        $this->pump = $pump;
        $this->performanceAsArray = $this->performanceAsArray($pump->{$performance});
    }

    public static function construct($pump)
    {
        if ($pump->pumpable_type === Pump::$DOUBLE_PUMP) {
            return new DPumpPerformance($pump);
        }
        return new static($pump, 'sp_performance');
    }

    protected function performanceAsArray($performance): array
    {
        return array_map(function ($value) {
            return floatval($value);
        }, explode(" ", $performance));
    }

    #[Pure] protected function pointDist(array $xx): float|int
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

    public function coefficientsToCreate($position): array
    {
        $coefficients = PolynomialRegression::fromData($this->asArrayData($position))->coefficients();
        return [
            'pump_id' => $this->pump->id,
            'position' => $position,
            'k' => $coefficients[0],
            'b' => $coefficients[1],
            'c' => $coefficients[2],
        ];
    }

    public function coefficientsForPosition($position)
    {
        $coefficients = $this->pump->coefficients->firstWhere('position', $position);
        return $coefficients ?? PumpsAndCoefficients::create($this->coefficientsToCreate($position));
    }

    public function hMax($head = 0,  $position = 1): float
    {
        $max = max($head, max(array_map(fn(Point $point) => $point->y(), $this->asPointArray($position))));
        return $max + ($max < 10 ? 3 : 10);
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

        for ($x = $xx[0]; $x <= $xx[$xxLength - 1]; $x += $qDist * $position) {
            $line[] = (new Point(
                $x,
                $regression->calculatedY($x)
            ))->asArray();
        }
        if ($line[count($line) - 1]['x'] != $xx[$xxLength - 1]) {
            $line[] = (new Point(
                $xx[$xxLength - 1],
                $regression->calculatedY($xx[$xxLength - 1])
            ))->asArray();
        }
        return $line;
    }
}
