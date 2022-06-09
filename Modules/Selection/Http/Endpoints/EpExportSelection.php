<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkDownloadPDF;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizeProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqExportSelection;
use Modules\Selection\Takes\TxtExportSelectionView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections export endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpExportSelection extends Controller
{
    /**
     * @param RqExportSelection $request
     * @param $selection_id
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqExportSelection $request, $selection_id): Responsable|Response
    {
        $selection = Selection::withOrWithoutTrashed()->findOrFail($selection_id);
        return (new TkDownloadPDF(
            new TxtExportSelectionView($selection, $request)
        ))->act($request);
    }
}
