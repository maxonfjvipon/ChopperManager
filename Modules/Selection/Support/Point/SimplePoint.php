<?php

namespace Modules\Selection\Support\Point;

/**
 * Simple point
 */
final class SimplePoint implements Point
{
    /**
     * @var float $x
     */
    private float $x;

    /**
     * @var float $y
     */
    private float $y;

    /**
     * Ctor wrap.
     * @param float $x
     * @param float $y
     * @return SimplePoint
     */
    public static function new(float $x, float $y): SimplePoint
    {
        return new self($x, $y);
    }

    /**
     * Ctor.
     * @param float $x
     * @param float $y
     */
    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
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

    public function x(): float
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }

}
