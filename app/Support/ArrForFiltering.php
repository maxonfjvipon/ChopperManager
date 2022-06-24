<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Tests\Unit\ArrForFilteringTest;

/**
 * Array that represent data in format to filtering on front-end
 * @see https://ant.design/components/table/#components-table-demo-head
 * @package App\Support
 * @see ArrForFilteringTest
 */
final class ArrForFiltering extends ArrEnvelope
{
    /**
     * Ctor.
     * @param array|Arrayable $data
     */
    public function __construct(private array|Arrayable $data)
    {
        parent::__construct(
            ArrMapped::new(
                $this->data,
                fn($item) => ArrMapped::new(
                    $item,
                    fn($value) => [
                        'text' => $value,
                        'value' => $value
                    ],
                )->asArray()
            )
        );
    }
}
