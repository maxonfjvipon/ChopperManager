<?php


namespace Modules\PumpManager\Services\Pump;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;
use Modules\Pump\Services\PumpBrands\PumpBrandsService;

class PMPumpBrandsService extends PumpBrandsService
{
    public function store(PumpBrandStoreRequest $request): RedirectResponse
    {
        PumpBrand::create($request->validated());
        return Redirect::route('pump_series.index');
    }
}
