<?php

namespace Modules\Pump\Services\PumpSeries;

use App\Services\ModuleResourceServiceInterface;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;

interface PumpSeriesServiceInterface extends ModuleResourceServiceInterface
{
    public function __index();

    public function __store(PumpSeriesStoreRequest $request);
}
