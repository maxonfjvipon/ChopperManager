<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkDownloadedPDF;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Takes\TxtExportSelectionView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections export at once endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class SelectionsExportAtOnceEndpoint extends Controller
{
    /**
     * @param ExportAtOnceSelectionRequest $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(ExportAtOnceSelectionRequest $request): Responsable|Response
    {
        return TkAuthorized::new(
            'selection_export',
            TkDownloadedPDF::new(
                TxtExportSelectionView::new(
                    new Selection($request->validated()),
                    $request
                )
            )
        )->act($request);
    }
}
