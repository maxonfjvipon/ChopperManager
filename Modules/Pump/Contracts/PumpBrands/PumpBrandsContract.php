<?php

namespace Modules\Pump\Contracts\PumpBrands;

use App\Services\ResourceWithRoutes\ResourceWithCreateRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithEditRouteInterface;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;

interface PumpBrandsContract extends
    ResourceWithCreateRouteInterface,
    ResourceWithEditRouteInterface
{
    public function store(PumpBrandStoreRequest $request);
}
