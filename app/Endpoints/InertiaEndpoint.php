<?php

namespace App\Endpoints;

use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inertia endpoint.
 * @package App\Endpoints
 */
class InertiaEndpoint implements Renderable
{
    /**
     * @var string $component
     */
    private string $component;

    /**
     * @var array
     */
    private array $props;

    /**
     * Ctor.
     * @param string $component
     * @param array $props
     */
    public function __construct(string $component, array $props = [])
    {
        $this->component = $component;
        $this->props = $props;
    }

    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        return Inertia::render($this->component, $this->props);
    }
}
