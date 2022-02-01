<?php

namespace Modules\Selection\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections restore endpoint.
 * @package Modules\Selection\Endpoints
 */
class SelectionsRestoreEndpoint extends Controller
{
    /**
     * @param $id
     * @return Responsable|Response
     */
    public function __invoke($id): Responsable|Response
    {
        $selection = Selection::withTrashed()->find($id);
        return TkAuthorizedProject::byId(
            $selection->project_id,
            TkAuthorized::new(
                'selection_restore',
                TkWithCallback::new(
                    fn() => $selection->restore(),
                    TkRedirectedBack::new()
                )
            )
        )->act();
    }
}
