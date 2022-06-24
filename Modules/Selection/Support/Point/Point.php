<?php

namespace Modules\Selection\Support\Point;

use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * Point.
 */
interface Point extends Arrayable
{
    /**
     * @return int|float
     */
    public function x(): int|float;

    /**
     * @return int|float
     */
    public function y(): int|float;
}
