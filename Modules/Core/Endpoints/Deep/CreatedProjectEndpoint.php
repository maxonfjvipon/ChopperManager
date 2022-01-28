<?php

namespace Modules\Core\Endpoints\Deep;

use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint where project is created for user from request data.
 * @package Modules\Core\Endpoints\Deep
 */
class CreatedProjectEndpoint implements Renderable
{
    /**
     * @var Renderable $origin
     */
    private Renderable $origin;

    /**
     * @param Renderable $renderable
     */
    public function __construct(Renderable $renderable)
    {
        $this->origin = $renderable;
    }

    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        Auth::user()->projects()->create($request->validated());
        return $this->origin->render($request);
    }
}
