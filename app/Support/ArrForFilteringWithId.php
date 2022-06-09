<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Plucked collection.
 */
final class ArrForFilteringWithId implements Arrayable
{
    public function __construct(private array $data, private string $attrName = 'name')
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMapped(
            $this->data,
            fn($obj) => (new ArrMapped(
                $obj,
                fn($object) => [
                    'text' => $object['name'] ?? $object['value'] ?? $object[$this->attrName],
                    'value' => $object['id']
                ]
            ))->asArray()
        ))->asArray();
    }
}
