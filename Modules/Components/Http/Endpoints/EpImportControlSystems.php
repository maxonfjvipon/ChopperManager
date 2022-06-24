<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Http\Requests\RqUploadFiles;
use Exception;
use Modules\Components\Actions\Import\ControlSystems\AcImportControlSystems;

/**
 * Import control systems endpoint.
 */
final class EpImportControlSystems extends TakeEndpoint
{
    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct(RqUploadFiles $request)
    {
        parent::__construct(
            new AcImportControlSystems($request->files()),
            $request
        );
    }
}
