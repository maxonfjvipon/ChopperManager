<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use App\Http\Requests\RqUploadFiles;
use Modules\Pump\Actions\AcImportPumps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Import pumps endpoint.
 */
final class EpImportPumps extends Controller
{
    /**
     * @param RqUploadFiles $request
     * @return Responsable|Response
     */
    public function __invoke(RqUploadFiles $request): Responsable|Response
    {
        return (new AcImportPumps($request->file('files')))->execute();
    }
}
