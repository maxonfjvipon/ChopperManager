<?php

namespace App\Endpoints;

use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that authorize user abilities
 * @package App\Endpoints
 */
class AuthorizedEndpoint implements Renderable
{
    use AuthorizesRequests;

    /**
     * @var string $ability
     */
    private string $ability;

    /**
     * @var Renderable $origin
     */
    private Renderable $origin;

    /**
     * Ctor.
     * @param Renderable $origin
     * @param string $ability
     */
    public function __construct(string $ability, Renderable $origin, )
    {
        $this->ability = $ability;
        $this->origin = $origin;
    }

    /**
     * @param Request|null $request
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        $this->authorize($this->ability);
        return $this->origin->render($request);
    }
}
