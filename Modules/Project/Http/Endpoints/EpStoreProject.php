<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkWithCallback;
use Modules\Project\Actions\AcStoreProject;
use Modules\Project\Http\Requests\RqStoreProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;

/**
 * Store project endpoint.
 */
final class EpStoreProject extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqStoreProject $request)
    {
        parent::__construct(
            new TkWithCallback(
                new AcStoreProject($request),
                new TkRedirectToProjectsIndex()
            ),
            $request
        );
    }
}
