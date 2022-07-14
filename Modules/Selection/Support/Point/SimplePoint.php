<?php

namespace Modules\Selection\Support\Point;

use JetBrains\PhpStorm\ArrayShape;

/**
 * Simple point.
 */
final class SimplePoint implements Point
{
    /**
     * Ctor.
     */
    public function __construct(private float $x, private float $y)
    {
    }

    #[ArrayShape(['x' => 'float', 'y' => 'float'])]
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
