<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Plucked collection.
 */
final class ArrForFilteringWithId extends ArrEnvelope
{
    /**
     * Ctor.
     * @param array $data
     * @param string $attrName
     */
    public function __construct(private array $data, private string $attrName = 'name')
    {
        parent::__construct(
            new ArrMapped(
                $this->data,
                fn($obj) => array_map(
                    fn($object) => [
                        'text' => $object['name'] ?? $object['value'] ?? $object[$this->attrName],
                        'value' => $object['id']
                    ],
                    $obj
                )
            )
        );
    }
}
