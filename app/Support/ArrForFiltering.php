<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Tests\Unit\ArrForFilteringTest;

/**
 * Array that represent data in format to filtering on front-end
 * @see https://ant.design/components/table/#components-table-demo-head
 * @package App\Support
 * @see ArrForFilteringTest
 */
final class ArrForFiltering implements Arrayable
{
    /**
     * @var array|Arrayable $data ;
     */
    private Arrayable|array $data;

    /**
     * Ctor.
     * @param array|Arrayable $data
     */
    public function __construct(array|Arrayable $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMapped(
            $this->data,
            fn($item) => (new ArrMapped(
                $item,
                fn($value) => [
                    'text' => $value,
                    'value' => $value
                ]
            ))->asArray()
        ))->asArray();
    }
}
