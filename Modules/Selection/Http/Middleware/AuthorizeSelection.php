<?php

namespace Modules\Selection\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Traits\AuthorizeProject;
use Modules\Selection\Entities\Selection;

final class AuthorizeSelection
{
    use AuthorizeProject;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->user()->isAdmin()) {
            abort_if(Auth::id() !== Selection::findOrFail($request->route()->originalParameter('selection'))->created_by, 404);
        }

        return $next($request);
    }
}
