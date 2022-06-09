<?php

namespace Modules\Selection\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Entities\Project;
use Modules\Project\Traits\AuthorizeProject;
use Modules\Selection\Entities\Selection;

final class AuthorizeProjectOfSelection
{
    use AuthorizeProject;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        abort_if(Auth::user()->cannot($this->authorizeProjectByIdPermission(
            Selection::withTrashed()->findOrFail(
                $request->route()->originalParameter('selection')
            )->project_id
        )), 403);
        return $next($request);
    }
}
