<?php

namespace Modules\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantIsActive
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
        if ($this->getTenantModel()::current()->is_active == false) {
            $this->handleTenantDisabled();
        }
        return $next($request);
    }

    protected function handleTenantDisabled()
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
