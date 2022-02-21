<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Point\SimplePoint;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Support\Regression\Equation;

/**
 * Pump performance line
 */
final class PumpPerfLine implements Arrayable
{
    /**
     * @var Pump $origin
     */
    private Pump $pump;

    /**
     * @var int $position
     */
    private int $position;

    /**
     * Ctor wrap.
     * @param Pump $pump
     * @param int $position
     * @return PumpPerfLine
     */
    public static function new(Pump $pump, int $position): PumpPerfLine
    {
        return new self($pump, $position);
    }

    /**
     * Ctor.
     * @param Pump $pump
     * @param int $position
     */
    public function __construct(Pump $pump, int $position)
    {
        $this->pump = $pump;
        $this->position = $position;
    }

    /**
     * @throws Exception
     */
    public function asArray(): array
    {
        $xx = ArrMapped::new(
            $this->pump->performance()->asArrayAt($this->position),
            fn (array $point) => $point[0]
        )->asArray();
        $qStep = $this->qStep($xx);
        $length = LengthOf::new($xx)->asNumber();
        $eq = EqFromPumpCoefficients::new($this->pump->coefficientsAt($this->position));
        $line = [];
        for ($x = $xx[0]; $x < $xx[$length - 1]; $x += $qStep * $this->position) {
            $line[] = $this->linePoint($x, $eq);
        }
        if ($line[count($line) - 1]['x'] != $xx[$length - 1]) {
            $line[] = $this->linePoint($xx[$length - 1], $eq);
        }
        return $line;
    }

    /**
     * @param array $xx
     * @return float|int
     */
    private function qStep(array $xx): float|int
    {
        $graphicsDist = $xx[count($xx) - 1] - $xx[0];
        foreach ([0.5, 1, 2, 5, 10, 20] as $step) {
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
        return SimplePoint::new($x, $eq->y($x))->asArray();
    }
}
