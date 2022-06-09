<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkDownloadPDF;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqExportAtOnceSelection;
use Modules\Selection\Takes\TxtExportSelectionView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Export selection at once endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpExportSelectionAtOnce extends Controller
{
    /**
     * @param RqExportAtOnceSelection $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqExportAtOnceSelection $request): Responsable|Response
    {
        return (new TkDownloadPDF(
            new TxtExportSelectionView(
                new Selection($request->validated()),
                $request
            )
        ))->act($request);
    }
}
