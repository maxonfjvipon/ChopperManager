<?php

namespace Modules\Selection\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkDownloadedPDF;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\ExportSelectionRequest;
use Modules\Selection\Takes\TxtExportSelectionView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections export endpoint.
 * @package Modules\Selection\Endpoints
 */
final class SelectionsExportEndpoint extends Controller
{
    /**
     * @param ExportSelectionRequest $request
     * @param Selection $selection
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(ExportSelectionRequest $request, Selection $selection): Responsable|Response
    {
        return TkAuthorizedProject::byId(
            $selection->project_id,
            TkAuthorized::new(
                'selection_export',
                TkDownloadedPDF::new(
                    TxtExportSelectionView::new($selection, $request)
                )
            )
        )->act($request);
    }
}
