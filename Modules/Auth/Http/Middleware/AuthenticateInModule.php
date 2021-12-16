<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;


class AuthenticateInModule extends Middleware
{
    use UsesTenantModel;

    public function handle($request, Closure $next, ...$guards)
    {
        $tenantModel = $this->getTenantModel();
        if ($tenantModel::checkCurrent()) {
            if (!$this->auth->guard($tenantModel::current()->guard)->check()) {
                $this->unauthenticated($request, $guards);
            }
        }
        $this->auth->shouldUse($tenantModel::current()->guard);
        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
