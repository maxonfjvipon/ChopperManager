<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Http\Requests\RqUploadFiles;
use Modules\Pump\Actions\AcImportPumps;

/**
 * Import pumps endpoint.
 */
final class EpImportPumps extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUploadFiles $request
     */
    public function __construct(RqUploadFiles $request)
    {
        parent::__construct(new AcImportPumps($request->files()), $request);
    }
}
