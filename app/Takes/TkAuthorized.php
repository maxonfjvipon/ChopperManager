<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkAuthorizedTest;

/**
 * Endpoint that authorize user abilities
 * @package App\Takes
 * @see TkAuthorizedTest
 */
final class TkAuthorized implements Take
{
    use AuthorizesRequests;

    /**
     * @var string $ability
     */
    private string $ability;

    /**
     * @var Take $origin
     */
    private Take $origin;

    /**
     * Ctor.
     * @param Take $origin
     * @param string $ability
     */
    public function __construct(string $ability, Take $origin)
    {
        $this->ability = $ability;
        $this->origin = $origin;
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
