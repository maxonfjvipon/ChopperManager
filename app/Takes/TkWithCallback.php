<?php

namespace App\Takes;

use App\Interfaces\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkWithCallbackTest;

/**
 * Take that calls function.
 *
 * @see TkWithCallbackTest
 */
final class TkWithCallback implements Take
{
    /**
     * @var callable
     */
    private $callback;

    private Take $origin;

    /**
     * Ctor.
     */
    public function __construct(callable $callback, Take $take)
    {
        $this->callback = $callback;
        $this->origin = $take;
    }

    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        call_user_func($this->callback);

        return $this->origin->act($request);
    }
}
