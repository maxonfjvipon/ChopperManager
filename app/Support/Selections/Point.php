<?php


namespace App\Support\Selections;

class Point
{
    private $x, $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function x()
    {
        return $this->x;
    }

    public function y()
    {
        return $this->y;
    }

    public function asArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }
}
