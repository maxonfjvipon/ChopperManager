<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections restore endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class SelectionsRestoreEndpoint extends Controller
{
    /**
     * @param $id
     * @return Responsable|Response
     */
    public function __invoke($id): Responsable|Response
    {
        $selection = Selection::withTrashed()->find($id);
        return (new TkAuthorizedProject(
            $selection->project_id,
            new TkAuthorized(
                'selection_restore',
                new TkWithCallback(
                    fn() => $selection->restore(),
                    new TkRedirectedBack()
                )
            )
        ))->act();
    }
}
