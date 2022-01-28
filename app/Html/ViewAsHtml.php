<?php

namespace App\Html;

use App\Support\Html;

/**
 * View as html
 * @package App\Html
 */
class ViewAsHtml implements Html
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
    public function __construct(string $name, array $data)
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
