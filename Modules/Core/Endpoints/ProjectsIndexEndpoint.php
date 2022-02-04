<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects index endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'project_access',
            TkInertia::new(
                "Core::Projects/Index",
                fn() => ['projects' => auth()->user()
                    ->projects()
                    ->withCount('selections')
                    ->with(['selections' => function ($query) {
                        $query->select('id', 'project_id', 'selected_pump_name', 'flow', 'head');
                    }])->get()
                ])
        )->act();
    }
}
