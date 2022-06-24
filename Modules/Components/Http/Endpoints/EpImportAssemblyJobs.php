<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Http\Requests\RqUploadFiles;
use Modules\Components\Actions\Import\AcImportAssemblyJobs;

/**
 * Import assembly jobs endpoint.
 */
final class EpImportAssemblyJobs extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUploadFiles $request
     */
    public function __construct(RqUploadFiles $request)
    {
        parent::__construct(
            new AcImportAssemblyJobs($request->files()),
            $request
        );
    }
}
