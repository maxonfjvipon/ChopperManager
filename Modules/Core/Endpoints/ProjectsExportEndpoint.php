<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkDownloadedPDF;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ExportProjectRequest;
use Modules\Core\Support\TxtExportProjectView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects export endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsExportEndpoint extends Controller
{
    /**
     * @param ExportProjectRequest $request
     * @param $project_id
     * @return Responsable|Response
     */
    public function __invoke(ExportProjectRequest $request, $project_id): Responsable|Response
    {
        $project = Project::withOrWithoutTrashed()->findOrFail($project_id);
        return TkAuthorizedProject::byProject(
            $project,
            TkAuthorized::new(
                'project_export',
                TkDownloadedPDF::new(
                    TxtExportProjectView::new($project, $request)
                )
            )
        )->act($request);
    }
}
