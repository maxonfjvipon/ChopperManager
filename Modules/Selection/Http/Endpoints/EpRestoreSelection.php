<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizeProject;
use Modules\Selection\Entities\Selection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restore selection endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpRestoreSelection extends Controller
{
    /**
     * @param int $selectionId
     * @return Responsable|Response
     */
    public function __invoke(int $selectionId): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => Selection::withTrashed()->find($selectionId)->restore(),
            new TkRedirectBack()
        ))->act();
    }
}
