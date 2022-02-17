<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Support\Pump\PumpsIndexData;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pumps index endpoint.
 */
final class PumpsIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new('pump_access',
            TkInertia::new("Pump::Pumps/Index",
                PumpsIndexData::new()
            )
        )->act();
    }
}
