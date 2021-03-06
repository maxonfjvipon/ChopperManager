<?php

namespace Modules\Project\Takes;

use App\Interfaces\Take;
use App\Takes\TkRedirectToRoute;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that redirects to projects index route.
 */
final class TkRedirectToProjectsIndex implements Take
{
    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkRedirectToRoute('projects.index'))->act($request);
    }
}
