<?php

namespace Modules\Selection\Support\PumpPerformance;

use JetBrains\PhpStorm\Pure;
use Modules\Selection\Support\Point;

class DPumpPerformance extends PPumpPerformance
{
    private array $dbPerformanceAsArray;

    public function __construct($pump)
    {
        parent::__construct($pump, 'dp_peak_performance');
        $this->dbPerformanceAsArray = $this->performanceAsArray($pump->dp_standby_performance);
    }

    protected function getPerformanceAsArray($position = 1): array
    {
        if ($position === 2) {
            return $this->dbPerformanceAsArray;
        }
        return $this->performanceAsArray;
    }

    #[Pure] public function qStart($position): float|int
    {
        return $this->getPerformanceAsArray($position)[0];
    }

    #[Pure] public function qEnd($position): float|int
    {
        return $this->getPerformanceAsArray($position)[count($this->getPerformanceAsArray($position)) - 2];
    }

    public function asArrayData($position): array
    {
        $paa = $this->getPerformanceAsArray($position);
        $data = [];
        $length = count($paa);
        for ($i = 0; $i < $length; $i += 2) {
            $data[] = [
                '0' => $paa[$i],
                '1' => $paa[$i + 1]
            ];
        }
        return $data;
    }

    public function asPointArray($position): array
    {
        $data = [];
        $paa = $this->getPerformanceAsArray($position);
        $length = count($paa);
        for ($i = 0; $i < $length; $i += 2) {
            $data[] = new Point($paa[$i], $paa[$i + 1]);
        }
        return $data;
    }

    public function hMax($head = 0, $position = 2): float
    {
        return parent::hMax($head, $position);
    }

    public function asRegressedLines($pc): array
    {
        $lines = [];
        for ($curPos = 1; $curPos <= $pc; ++$curPos) {
            $lines[] = $this->asRegressedPointArray($curPos);
        }
        return $lines;
    }
}
