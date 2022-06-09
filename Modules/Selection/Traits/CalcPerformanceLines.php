<?php

namespace Modules\Selection\Traits;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;
use Modules\Selection\Support\Point\SimplePoint;
use Modules\Selection\Support\Regression\Equation;

trait CalcPerformanceLines
{
    /**
     * @param array $xx
     * @return float|int
     */
    private function qStep(array $xx): float|int
    {
        $graphicsDist = $xx[count($xx) - 1] - $xx[0];
        foreach ([0.1, 0.5, 1, 2, 5, 10, 20] as $step) {
            if ($graphicsDist <= $step * 50) {
                return $step;
            }
        }
        return 20;
    }

    /**
     * @param float $x
     * @param Equation $eq
     * @return array
     */
    private function linePoint(float $x, Equation $eq): array
    {
        return (new SimplePoint($x, $eq->y($x)))->asArray();
    }

    /**
     * @param array $xx
     * @param Equation $eq
     * @param int $pos
     * @return array
     * @throws Exception
     */
    private function calcLine(array $xx, Equation $eq, int $pos = 1): array
    {
        $qStep = $this->qStep($xx) * $pos / 2;
        $length = (new LengthOf($xx))->asNumber();
        $line = [];
        for ($x = $xx[0]; $x < $xx[$length - 1]; $x += $qStep) {
            $line[] = $this->linePoint($x, $eq);
        }
        if ($line[count($line) - 1]['x'] !== $xx[$length - 1]) {
            $line[] = $this->linePoint($xx[$length - 1], $eq);
        }
        return $line;
    }
}
