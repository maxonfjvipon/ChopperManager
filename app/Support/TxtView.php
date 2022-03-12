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
     * @var string $name
     */
    private string $name;

    /**
     * @var array $data
     */
    private array $data;

    /**
     * Ctor.
     * @param string $name
     * @param array $data
     */
    public function __construct(string $name, array $data = [])
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
