<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOverloaded;
use Maxonfjvipon\Elegant_Elephant\Text;
use Tests\Unit\TxtViewTest;

/**
 * View as html.
 *
 * @see TxtViewTest
 */
final class TxtView implements Text
{
    use ArrayableOverloaded;

    public static function new(string $name, Arrayable|array $data = []): TxtView
    {
        return new self($name, $data);
    }

    /**
     * Ctor.
     *
     * @param string          $name
     * @param Arrayable|array $data
     */
    public function __construct(private string $name, private Arrayable|array $data = [])
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asString(): string
    {
        return view($this->name, $this->firstArrayableOverloaded($this->data))->render();
    }
}
