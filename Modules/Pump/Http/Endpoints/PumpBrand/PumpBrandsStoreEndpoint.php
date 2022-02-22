<?php

namespace Modules\Pump\Http\Endpoints\PumpBrand;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pump brands store endpoint.
 */
final class PumpBrandsStoreEndpoint extends Controller
{
    /**
     * @param PumpBrandStoreRequest $request
     * @return Responsable|Response
     */
    public function __invoke(PumpBrandStoreRequest $request): Responsable|Response
    {
        return TkAuthorized::new(
            'brand_create',
            TkWithCallback::new(
                fn() => PumpBrand::create($request->validated()),
                TkRedirectedRoute::new('pump_series.index')
            )
        )->act($request);
    }
}
