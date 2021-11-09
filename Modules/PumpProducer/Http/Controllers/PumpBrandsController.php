<?php

namespace Modules\PumpProducer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;
use Modules\Pump\Http\Requests\PumpBrandUpdateRequest;
use Modules\Pump\Transformers\PumpBrandResource;
use Modules\PumpProducer\Entities\User;

class PumpBrandsController extends \Modules\Pump\Http\Controllers\PumpBrandsController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param PumpBrandStoreRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(PumpBrandStoreRequest $request): RedirectResponse
    {
        $this->authorize('brand_create');
        $brand = PumpBrand::create($request->validated());
        DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($userId) => [
            'user_id' => $userId, 'discountable_type' => 'pump_brand', 'discountable_id' => $brand->id
        ], User::pluck('id')->all()));
        return Redirect::route('pump_series.index');
    }
}
