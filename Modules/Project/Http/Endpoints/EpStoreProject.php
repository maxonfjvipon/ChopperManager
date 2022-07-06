<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkWithCallback;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Http\Requests\RqStoreProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;

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
