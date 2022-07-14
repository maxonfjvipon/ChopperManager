<?php

namespace Modules\Selection\Support\Point;

use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * Point.
 */
interface Point extends Arrayable
{
    public function x(): int|float;

    public function y(): int|float;
}
