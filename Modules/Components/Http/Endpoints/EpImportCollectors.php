<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Http\Requests\RqUploadFiles;
use Modules\Components\Actions\Import\AcImportCollectors;

/**
 * Import collectors endpoint.
 */
final class EpImportCollectors extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUploadFiles $request)
    {
        parent::__construct(
            new AcImportCollectors($request->files()),
            $request
        );
    }
}
