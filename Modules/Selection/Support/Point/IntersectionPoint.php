<?php

namespace Modules\Selection\Support\Point;

use Exception;
use Modules\Selection\Support\Regression\Equation;

/**
 * Intersection point.
 */
final class IntersectionPoint implements Point
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
     * @var array $cache
     */
    private array $cache = [];

    /**
     * @var Equation $equation
     */
    private Equation $equation;

    /**
     * @param Equation $equation
     * @param float $flow
     * @param float $head
     */
    public function __construct(Equation $equation, float $flow, float $head)
    {
        $this->equation = $equation;
        $this->flow = $flow;
        $this->head = $head;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return $this->cache['point'] ?? $this->cache['point'] = (function () {
                $z = $this->head / ($this->flow * $this->flow);
                $eq = $this->equation->asArray();
                $t = $eq[0] - $z;
                $x = (-$eq[1] - sqrt($eq[1] * $eq[1] - 4 * $t * $eq[2])) / (2 * $t);
                return [
                    'x' => $x,
                    'y' => $z * $x * $x
                ];
            })();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function x(): int|float
    {
        return $this->asArray()['x'];
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function y(): int|float
    {
        return $this->asArray()['y'];
    }
}
