<?php

namespace Modules\PumpProducer\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\PumpProducer\Entities\User;

class PumpSeriesController extends \Modules\Pump\Http\Controllers\PumpSeriesController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('series_access');
        $this->authorize('brand_access');
        $series = PumpSeries::with(['brand', 'category', 'power_adjustment'])
            ->get()
            ->map(fn($series) => [
                'id' => $series->id,
                'brand' => $series->brand->name,
                'name' => $series->name,
                'category' => $series->category->name,
                'power_adjustment' => $series->power_adjustment->name,
                'applications' => $series->imploded_applications,
                'types' => $series->imploded_types
            ])
            ->all();
        return Inertia::render('Pump::PumpSeries/Profile', [
            'filter_data' => $this->indexFilterData(),
            'brands' => PumpBrand::all(),
            'series' => $series,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PumpSeriesStoreRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(PumpSeriesStoreRequest $request): RedirectResponse
    {
        $this->authorize('series_create');
        $series = PumpSeries::createFromRequest($request);
        DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($userId) => [
            'user_id' => $userId, 'discountable_type' => 'pump_series', 'discountable_id' => $series->id
        ], User::pluck('id')->all()));
        return Redirect::route('pump_series.index');
    }
}
