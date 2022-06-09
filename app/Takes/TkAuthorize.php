<?php

namespace App\Takes;

use App\Takes\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkAuthorizedTest;

/**
 * Endpoint that authorize user abilities
 * @package App\Takes
 * @see TkAuthorizedTest
 */
final class TkAuthorize implements Take
{
    use AuthorizesRequests;

    /**
     * Ctor.
     * @param Take $origin
     * @param string $ability
     */
    public function __construct(private string $ability, private Take $origin)
    {
    }

    /**
     * @param Request|null $request
     * @return Responsable|Response
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $this->authorize($this->ability),
            $this->origin
        ))->act($request);
    }
}
