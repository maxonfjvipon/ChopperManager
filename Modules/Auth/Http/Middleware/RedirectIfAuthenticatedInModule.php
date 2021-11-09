<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class RedirectIfAuthenticatedInModule
{
    use UsesTenantModel;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantModel = $this->getTenantModel();
        if ($tenantModel::checkCurrent()) {
            if (Auth::guard($tenantModel::current()->getGuard())->check()) {
                return Redirect::route('projects.index');
            }
        }
        return $next($request);
    }
}
