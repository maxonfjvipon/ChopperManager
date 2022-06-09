<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Http\Requests\RqStoreProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;
use Symfony\Component\HttpFoundation\Response;

/**
 * Store project endpoint.
 * @package Modules\Project\Takes
 * @see Project booted method
 */
final class EpStoreProject extends Controller
{
    /**
     * @param RqStoreProject $request
     * @return Responsable|Response
     */
    public function __invoke(RqStoreProject $request): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => Auth::user()->projects()->create($request->projectProps()),
            new TkRedirectToProjectsIndex()
        ))->act($request);
    }
}
