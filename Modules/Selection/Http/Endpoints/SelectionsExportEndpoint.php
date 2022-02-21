<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkDownloadedPDF;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\ExportSelectionRequest;
use Modules\Selection\Takes\TxtExportSelectionView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections export endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class SelectionsExportEndpoint extends Controller
{
    /**
     * @param ExportSelectionRequest $request
     * @param $selection_id
     * @return Responsable|Response
     */
    public function __invoke(ExportSelectionRequest $request, $selection_id): Responsable|Response
    {
        $selection = Selection::withOrWithoutTrashed()->findOrFail($selection_id);
        return (new TkAuthorizedProject(
            $selection->project_id,
            new TkAuthorized(
                'selection_export',
                new TkDownloadedPDF(
                    new TxtExportSelectionView($selection, $request)
                )
            )
        ))->act($request);
    }
}
