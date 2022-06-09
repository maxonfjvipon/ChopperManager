<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Models\Enums\Country;
use Exception;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Pump brands create endpoint.
 */
final class EpCreatePumpBrand extends Controller
{
    /**
     * @return Response|ResponseFactory
     * @throws Exception
     */
    public function __invoke(): Response|ResponseFactory
    {
        return inertia("PumpSeries::PumpBrands/Create", [
            'countries' => Country::asArrayForSelect()
        ]);
    }
}
