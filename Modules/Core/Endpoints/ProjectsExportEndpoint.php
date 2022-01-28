<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Endpoints\PDFDownloadedFromHtmlEndpoint;
use App\Html\ViewAsHtml;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Endpoints\Deep\AuthorizedProjectEndpoint;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ExportProjectRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects export endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsExportEndpoint extends Controller implements Renderable
{
    /**
     * @var Project $project
     */
    private Project $project;

    /**
     * @param ExportProjectRequest $request
     * @param Project $project
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(ExportProjectRequest $request, Project $project): Responsable|Response
    {
        $this->project = $project;
        return $this->render($request);
    }

    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedProjectEndpoint(
            $this->project,
            new AuthorizedEndpoint(
                'project_export',
                new PDFDownloadedFromHtmlEndpoint(
                    new ViewAsHtml('core::project_export', [
                        'project' => $this->project->readyForExport($request),
                        'request' => $request
                    ])
                )
            )
        ))->render($request);
    }
}
