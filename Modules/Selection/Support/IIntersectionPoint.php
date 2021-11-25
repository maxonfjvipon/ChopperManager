<?php

namespace Modules\Selection\Support;

class IIntersectionPoint extends Point
{
    public function __construct($coefficients, $flow, $head)
    {
        $z = $head / ($flow * $flow);
        $t = $coefficients->k - $z;
        $d = $coefficients->b * $coefficients->b - 4 * $t * $coefficients->c;
        $sqrtD = sqrt($d);
        $x = (-$coefficients->b - $sqrtD) / (2 * $t);
        parent::__construct($x, $z * $x * $x);
    }
}
