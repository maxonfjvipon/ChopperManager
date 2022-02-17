<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Array that represent data in format to filtering on front-end
 * @see https://ant.design/components/table/#components-table-demo-head
 * @package App\Support
 */
final class ArrForFiltering implements Arrayable
{
    /**
     * @var array|Arrayable $data;
     */
    private Arrayable|array $data;

    /**
     * @param array|Arrayable $data
     * @return ArrForFiltering
     */
    public static function new(array|Arrayable $data)
    {
        return new self($data);
    }

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
        return ArrMapped::new(
            $this->data,
            fn($item) => ArrMapped::new(
                $item,
                fn($value) => [
                    'text' => $value,
                    'value' => $value
                ]
            )->asArray()
        )->asArray();
    }
}
