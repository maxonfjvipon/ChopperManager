<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::user()->is_active) {
            Auth::logout();
            abort(401, 'Your account is disabled');
        }

        return $next($request);
    }
}
