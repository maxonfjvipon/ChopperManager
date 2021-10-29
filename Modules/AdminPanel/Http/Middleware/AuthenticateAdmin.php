<?php

namespace Modules\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateAdmin extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!$this->auth->guard('admin')->check()) {
            $this->unauthenticated($request, $guards);
        }
        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return route('admin.login');
        }
    }
}
