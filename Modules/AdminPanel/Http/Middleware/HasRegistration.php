<?php

namespace Modules\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class HasRegistration
{
    use UsesTenantModel;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantModel = $this->getTenantModel();
        if ($tenantModel::checkCurrent()) {
            if (!$tenantModel::current()->has_registration) {
                return Redirect::route('login');
            }
        }
        return $next($request);
    }
}
