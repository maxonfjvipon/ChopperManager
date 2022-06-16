<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Points for position
 */
final class PointsForPos implements Arrayable
{
    /**
     * Ctor.
     * @param array|Arrayable $origin
     * @param int $position
     */
    public function __construct(private array|Arrayable $origin, private int $position = 1) {}

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return ArrMapped::new(
            $this->origin,
            fn(array $point) => [$point[0] * $this->position, $point[1]]
        )->asArray();
    }
}
