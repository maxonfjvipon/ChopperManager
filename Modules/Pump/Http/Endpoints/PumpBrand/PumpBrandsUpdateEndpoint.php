<?php

namespace Modules\Pump\Http\Endpoints\PumpBrand;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

final class PumpBrandsUpdateEndpoint extends Controller
{
    /**
     * @param PumpBrandUpdateRequest $request
     * @param PumpBrand $pumpBrand
     * @return Responsable|Response
     */
    public function __invoke(PumpBrandUpdateRequest $request, PumpBrand $pumpBrand): Responsable|Response
    {
        return TkAuthorized::new(
            'brand_edit',
            TkWithCallback::new(
                fn() => $pumpBrand->update($request->validated()),
                TkRedirectedRoute::new('pump_series.index')
            )
        )->act($request);
    }
}
