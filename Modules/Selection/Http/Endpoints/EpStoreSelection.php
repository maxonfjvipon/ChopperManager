<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkRedirectWith;
use App\Takes\TkWithCallback;
use Modules\Project\Entities\Project;
use Modules\Selection\Actions\AcStoreSelection;
use Modules\Selection\Http\Requests\RqStoreSelection;

/**
 * Store selection endpoint.
 */
final class EpStoreSelection extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqStoreSelection $request)
    {
        parent::__construct(
            new TkWithCallback(
                new AcStoreSelection($request, $project = Project::find($request->project)),
                new TkRedirectWith(
                    'success',
                    __('flash.selections.added'),
                    new TkRedirectToRoute('projects.show', $project->id)
                )
            )
        );
    }
}
