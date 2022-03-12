<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fake take.
 */
final class TkFake implements Take
{
    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return new Response();
    }
}
