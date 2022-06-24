<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Interfaces\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Http\Requests\RqStoreProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;
use Symfony\Component\HttpFoundation\Response;

/**
 * Store project endpoint.
 * @package Modules\Project\Takes
 */
final class EpStoreProject extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqStoreProject $request
     */
    public function __construct(RqStoreProject $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => Auth::user()->projects()->create($request->projectProps()),
                new TkRedirectToProjectsIndex()
            ),
            $request
        );
    }
}
