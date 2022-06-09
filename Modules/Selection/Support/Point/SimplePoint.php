<?php

namespace Modules\Selection\Support\Point;

/**
 * Simple point
 */
final class SimplePoint implements Point
{
    /**
     * Ctor.
     * @param float $x
     * @param float $y
     */
    public function __construct(private float $x, private float $y)
    {
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
        ];
    }

    /**
     * @return float
     */
    public function x(): float
    {
        return $this->x;
    }

    /**
     * @return float
     */
    public function y(): float
    {
        return $this->y;
    }

}
