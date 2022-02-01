<?php

namespace Modules\Core\Takes;

use App\Takes\TkRedirectedRoute;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that redirects to projects index route.
 * @package Modules\Core\Takes\Deep
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
     * Ctor.
     */
    private function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return TkRedirectedRoute::new('projects.index')->act($request);
    }
}
