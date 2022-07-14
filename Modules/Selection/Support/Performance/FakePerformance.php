<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

final class FakePerformance implements PumpPerformance
{
    /**
     * Ctor.
     *
     * @param array $array
     */
    public function __construct(private array $array)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function asArrayAt(int $position): array
    {
        return (new ArrMapped(
            $this->array,
            fn (array $arr) => [$arr[0] * $position, $arr[1]]
        ))->asArray();
    }
}
