<?php


namespace Modules\Pump\Services\PumpBrands;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;

class PumpBrandsService implements PumpBrandsSeriesInterface
{
    public function indexPath(): string
    {
    }

    public function showPath(): string
    {
    }

    public function editPath(): string
    {
        return 'Pump::PumpBrands/Edit';
    }

    public function createPath(): string
    {
        return 'Pump::PumpBrands/Create';
    }

    public function __store(PumpBrandStoreRequest $request): RedirectResponse
    {
        PumpBrand::create($request->validated());
        return Redirect::route('pump_series.index');
    }

}
