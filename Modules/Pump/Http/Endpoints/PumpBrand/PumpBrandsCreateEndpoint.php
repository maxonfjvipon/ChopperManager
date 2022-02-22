<?php

namespace Modules\Pump\Http\Endpoints\PumpBrand;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pump brands create endpoint.
 */
final class PumpBrandsCreateEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'brand_create',
            TkInertia::new("Pump::PumpBrands/Create")
        )->act();
    }
}
