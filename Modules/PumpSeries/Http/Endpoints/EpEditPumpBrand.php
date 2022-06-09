<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Models\Enums\Country;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Actions\AcEditPumpBrand;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Transformers\RcPumpBrand;
use Symfony\Component\HttpFoundation\Response;

/**
 * Edit pump brand endpoint.
 */
final class EpEditPumpBrand extends Controller
{
    /**
     * @param PumpBrand $pumpBrand
     * @param AcEditPumpBrand $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(PumpBrand $pumpBrand, AcEditPumpBrand $action): Responsable|Response
    {
        return inertia("PumpSeries::PumpBrands/Edit", $action($pumpBrand));
    }
}
