<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkAuthorize;
use App\Takes\TkRedirectBack;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkRedirectWith;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Auth;
use DB;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizeProject;
use Modules\Project\Entities\Project;
use Modules\Selection\Actions\AcStoreSelection;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqStoreSelection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Store selection endpoint.
 * @package Modules\Selection\Takes
 */
final class EpStoreSelection extends Controller
{
    /**
     * @param RqStoreSelection $request
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(RqStoreSelection $request, Project $project): Responsable|Response
    {
        return (new TkWithCallback(
            new AcStoreSelection($request, $project),
            new TkRedirectWith(
                'success',
                __('flash.selections.added'),
                new TkRedirectToRoute('projects.show', $project->id)
            )
        ))->act($request);
    }
}
