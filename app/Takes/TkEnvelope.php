<?php

namespace App\Takes;

use App\Interfaces\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Take envelope.
 */
class TkEnvelope implements Take
{
    /**
     * Ctor.
     */
    public function __construct(private Take $origin)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        return $this->origin->act($request);
    }
}
