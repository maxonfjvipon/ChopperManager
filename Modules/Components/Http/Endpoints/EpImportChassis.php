<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Http\Requests\RqUploadFiles;
use Modules\Components\Actions\Import\AcImportChassis;

/**
 * Import chassis endpoint.
 */
final class EpImportChassis extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUploadFiles $request)
    {
        parent::__construct(
            new AcImportChassis($request->files()),
            $request
        );
    }
}
