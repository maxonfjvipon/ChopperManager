<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkDownloadedPDF;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\ExportProjectRequest;
use Modules\Project\Support\TxtExportProjectView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects export endpoint.
 * @package Modules\Project\Takes
 */
final class ProjectsExportEndpoint extends Controller
{
    /**
     * @param ExportProjectRequest $request
     * @param int $project_id
     * @return Responsable|Response
     */
    public function __invoke(ExportProjectRequest $request, int $project_id): Responsable|Response
    {
        return (new TkAuthorizedProject(
            $project_id,
            new TkAuthorized(
                'project_export',
                new TkDownloadedPDF(
                    new TxtExportProjectView(
                        Project::withOrWithoutTrashed()->findOrFail($project_id),
                        $request
                    )
                )
            )
        ))->act($request);
    }
}
