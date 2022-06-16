<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Text;
use Tests\Unit\TxtViewTest;

/**
 * View as html
 * @package App\Support
 * @see TxtViewTest
 */
final class TxtView implements Text
{
    /**
     * @param string $name
     * @param array $data
     * @return TxtView
     */
    public static function new(string $name, array $data = []): TxtView
    {
        return new self($name, $data);
    }

    /**
     * Ctor.
     * @param string $name
     * @param array $data
     */
    public function __construct(private string $name, private array $data = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return view($this->name, $this->data)->render();
    }
}
