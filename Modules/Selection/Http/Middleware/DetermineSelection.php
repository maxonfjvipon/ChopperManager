<?php

namespace Modules\Selection\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

final class DetermineSelection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        abort_if(
            !in_array($request->station_type, StationType::getKeys())
            || !in_array($request->selection_type, SelectionType::getKeys()),
            404
        );

        return $next($request);
    }
}
