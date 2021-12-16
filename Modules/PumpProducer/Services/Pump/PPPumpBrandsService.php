<?php


namespace Modules\PumpProducer\Services\Pump;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;
use Modules\Pump\Services\PumpBrands\PumpBrandsService;
use Modules\PumpProducer\Entities\PPUser;

class PPPumpBrandsService extends PumpBrandsService
{
    public function store(PumpBrandStoreRequest $request): RedirectResponse
    {
        $brand = PumpBrand::create($request->validated());
        DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($userId) => [
            'user_id' => $userId, 'discountable_type' => 'pump_brand', 'discountable_id' => $brand->id
        ], PPUser::pluck('id')->all()));
        return Redirect::route('pump_series.index');
    }
}
