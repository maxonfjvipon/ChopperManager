<?php


namespace Modules\Pump\Services\PumpBrands;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Pump\Contracts\PumpBrands\PumpBrandsContract;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;

abstract class PumpBrandsService implements PumpBrandsContract
{
    public function editPath(): string
    {
        return 'Pump::PumpBrands/Edit';
    }

    public function createPath(): string
    {
        return 'Pump::PumpBrands/Create';
    }
}
