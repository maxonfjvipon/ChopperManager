<?php

namespace App\Endpoints;

use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect back endpoint.
 * @package App\Endpoints
 */
class RedirectBackEndpoint implements Renderable
{
    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        return Redirect::back();
    }
}
