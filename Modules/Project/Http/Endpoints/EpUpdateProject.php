<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\RqUpdateProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;
use Symfony\Component\HttpFoundation\Response;

/**
 * Update project endpoint.
 * @package Modules\Project\Takes
 * @see Project booted method
 */
final class EpUpdateProject extends Controller
{
    /**
     * @param RqUpdateProject $request
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(RqUpdateProject $request, Project $project): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $project->update($request->projectProps()),
            new TkRedirectToProjectsIndex()
        ))->act($request);
    }
}
