<?php

namespace Modules\Pump\Http\Endpoints\PumpBrand;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpBrand;
use Symfony\Component\HttpFoundation\Response;

final class PumpBrandsRestoreEndpoint extends Controller
{
    /**
     * @param int $brand_id
     * @return Responsable|Response
     */
    public function __invoke(int $brand_id): Responsable|Response
    {
        return (new TkAuthorized(
            'brand_restore',
            new TkWithCallback(
                fn() => PumpBrand::withTrashed()->find($brand_id)->restore(),
                new TkRedirectedRoute('pump_series.index')
            )
        ))->act();
    }
}
