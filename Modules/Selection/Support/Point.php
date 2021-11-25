<?php

namespace Modules\Selection\Support;

class Point
{
    private float $x, $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

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
