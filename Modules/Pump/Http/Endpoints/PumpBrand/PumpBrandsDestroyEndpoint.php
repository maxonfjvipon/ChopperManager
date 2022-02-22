<?php

namespace Modules\Pump\Http\Endpoints\PumpBrand;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpBrand;
use Symfony\Component\HttpFoundation\Response;

final class PumpBrandsDestroyEndpoint extends Controller
{
    /**
     * @param PumpBrand $pumpBrand
     * @return Responsable|Response
     */
    public function __invoke(PumpBrand $pumpBrand): Responsable|Response
    {
        return TkAuthorized::new(
            'brand_delete',
            TkWithCallback::new(
                fn() => $pumpBrand->delete(),
                TkRedirectedRoute::new('pump_series.index')
            )
        )->act();
    }
}