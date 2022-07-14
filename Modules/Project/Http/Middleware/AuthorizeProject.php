<?php

namespace Modules\Project\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Entities\Project;

final class AuthorizeProject
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->user()->isAdmin()) {
            abort_if(Auth::id() !== Project::findOrFail($request->route()->originalParameter('project'))->created_by, 404);
        }

        return $next($request);
    }
}
