<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Http\Requests\RqUploadFiles;
use Modules\Components\Actions\Import\AcImportArmature;

/**
 * Import armature endpoint.
 */
final class EpImportArmature extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUploadFiles $request
     */
    public function __construct(RqUploadFiles $request)
    {
        parent::__construct(
            new AcImportArmature($request->files()),
            $request
        );
    }
}
