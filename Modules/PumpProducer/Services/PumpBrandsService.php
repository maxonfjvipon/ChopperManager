<?php


namespace Modules\PumpProducer\Services;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;
use Modules\PumpProducer\Entities\User;

class PumpBrandsService extends \Modules\Pump\Services\PumpBrands\PumpBrandsService
{
    public function __store(PumpBrandStoreRequest $request): RedirectResponse
    {
        $brand = PumpBrand::create($request->validated());
        DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($userId) => [
            'user_id' => $userId, 'discountable_type' => 'pump_brand', 'discountable_id' => $brand->id
        ], User::pluck('id')->all()));
        return Redirect::route('pump_series.index');
    }
}
