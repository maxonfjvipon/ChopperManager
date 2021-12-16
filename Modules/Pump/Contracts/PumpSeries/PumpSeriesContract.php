<?php

namespace Modules\Pump\Contracts\PumpSeries;

use App\Services\ModuleResourceServiceInterface;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;

interface PumpSeriesContract extends ModuleResourceServiceInterface
{
    public function index();

    public function store(PumpSeriesStoreRequest $request);
}
