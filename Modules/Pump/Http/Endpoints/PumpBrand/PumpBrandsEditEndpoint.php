<?php

namespace Modules\Pump\Http\Endpoints\PumpBrand;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Transformers\PumpBrandResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pump brands edit endpoint.
 */
final class PumpBrandsEditEndpoint extends Controller
{
    /**
     * @param PumpBrand $pumpBrand
     * @return Responsable|Response
     */
    public function __invoke(PumpBrand $pumpBrand): Responsable|Response
    {
        return (new TkAuthorized(
            'brand_edit',
            new TkInertia("Pump::PumpBrands/Edit", [
                    'brand' => [
                        'data' => [
                            'id' => $pumpBrand->id,
                            'name' => $pumpBrand->name
                        ]
                    ]
                ]
            )
        ))->act();
    }
}
