<?php

namespace Modules\Project\Takes;

use App\Takes\TkRedirectedRoute;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that redirects to projects index route.
 * @package Modules\Project\Takes\Deep
 */
final class TkRedirectedToProjectsIndex implements Take
{
    /**
     * Ctor wrap.
     * @return TkRedirectedToProjectsIndex
     */
    public static function new(): TkRedirectedToProjectsIndex
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return TkRedirectedRoute::new('projects.index')->act($request);
    }
}
