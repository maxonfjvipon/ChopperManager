<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizeProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqStoreSelection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Update selection endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpUpdateSelection extends Controller
{
    /**
     * @param RqStoreSelection $request
     * @param Selection $selection
     * @return Responsable|Response
     */
    public function __invoke(RqStoreSelection $request, Selection $selection): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $selection->update($request->validated()),
            new TkRedirectToRoute(
                'projects.show',
                $selection->project_id
            )
        ))->act($request);
    }
}
