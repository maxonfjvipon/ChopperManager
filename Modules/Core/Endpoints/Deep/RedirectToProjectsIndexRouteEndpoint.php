<?php

namespace Modules\Core\Endpoints\Deep;

use App\Endpoints\RedirectRouteEndpoint;
use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that redirects to projects index route.
 * @package Modules\Core\Endpoints\Deep
 */
class RedirectToProjectsIndexRouteEndpoint implements Renderable
{
    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new RedirectRouteEndpoint('projects.index'))->render($request);
    }
}
