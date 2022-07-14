<?php

namespace Modules\Selection\Support\Point;

use Exception;
use Modules\Selection\Support\Regression\Equation;

/**
 * Intersection point.
 */
final class IntersectionPoint implements Point
{
    private array $cache = [];

    public function __construct(private Equation $equation, private float $flow, private float $head)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asArray(): array
    {
        return $this->cache['point'] ??= (function () {
            $z = $this->head / ($this->flow * $this->flow);
            $eq = $this->equation->asArray();
            $t = $eq[0] - $z;
            $x = (-$eq[1] - sqrt($eq[1] * $eq[1] - 4 * $t * $eq[2])) / (2 * $t);

            return [
                'x' => $x,
                'y' => $z * $x * $x,
            ];
        })();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function x(): int|float
    {
        return $this->asArray()['x'];
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function y(): int|float
    {
        return $this->asArray()['y'];
    }
}
