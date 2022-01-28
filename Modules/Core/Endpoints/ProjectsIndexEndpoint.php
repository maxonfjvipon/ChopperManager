<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Endpoints\InertiaEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects index endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsIndexEndpoint extends Controller implements Renderable
{
    /**
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(): Responsable|Response
    {
        return $this->render();
    }

    /**
     * @param Request|null $request
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_access',
            new InertiaEndpoint("Core::Projects/Index", [
                    'projects' => auth()->user()->projects()
                        ->withCount('selections')
                        ->with(['selections' => function ($query) {
                            $query->select('id', 'project_id', 'selected_pump_name', 'flow', 'head');
                        }])
                        ->get()
                ]
            )
        ))->render($request);
    }
}
