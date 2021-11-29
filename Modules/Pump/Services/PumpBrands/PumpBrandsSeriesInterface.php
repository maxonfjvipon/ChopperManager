<?php


namespace Modules\Pump\Services\PumpBrands;

use App\Services\ModuleResourceServiceInterface;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;

interface PumpBrandsSeriesInterface extends ModuleResourceServiceInterface
{
    public function __store(PumpBrandStoreRequest $request);
}
