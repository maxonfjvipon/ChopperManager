<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Points for position
 */
final class PointsForPos extends ArrEnvelope
{
    /**
     * Ctor.
     * @param array|Arrayable $origin
     * @param int $position
     */
    public function __construct(private array|Arrayable $origin, private int $position = 1)
    {
        parent::__construct(
            new ArrMapped(
                $this->origin,
                fn(array $point) => [$point[0] * $this->position, $point[1]]
            )
        );
    }
}
