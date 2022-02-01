<?php

namespace App\Support;

use Maxonfjvipon\Elegant_Elephant\Text;

/**
 * View as html
 * @package App\Support
 */
final class TxtView implements Text
{
    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var array $data
     */
    private array $data;

    /**
     * @param string $name
     * @param array $data
     * @return TxtView
     */
    public static function new(string $name, array $data): TxtView
    {
        return new self($name, $data);
    }

    /**
     * Ctor.
     * @param string $name
     * @param array $data
     */
    private function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return view($this->name, $this->data)->render();
    }
}
