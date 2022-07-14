<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Administrate
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        abort_if(!$request->user()->isAdmin(), 404);

        return $next($request);
    }
}
