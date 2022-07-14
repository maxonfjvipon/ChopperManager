<?php

namespace App\Takes;

use App\Interfaces\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\CastMixed;
use Maxonfjvipon\Elegant_Elephant\Text;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkInertiaTest;

/**
 * Inertia endpoint.
 *
 * @see TkInertiaTest
 */
final class TkInertia implements Take
{
    use CastMixed;

    /**
     * Ctor.
     *
     * @param string|Text     $component
     * @param array|Arrayable $props
     */
    public function __construct(private string|Text $component, private array|Arrayable $props = [])
    {
    }

    /**
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return Inertia::render(
            $this->castMixed($this->component),
            $this->castMixed($this->props)
        );
    }
}
