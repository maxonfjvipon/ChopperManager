<?php

namespace App\Http\Controllers;

use App\Http\Requests\PumpSeriesStoreRequest;
use App\Http\Requests\PumpSeriesUpdateRequest;
use App\Http\Resources\PumpSeriesPropsResource;
use App\Http\Resources\PumpSeriesResource;
use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpCategory;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpType;
use App\Models\Users\User;
use App\Traits\HasFilterData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PumpSeriesController extends Controller
{
    use HasFilterData;

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
        return Inertia::render('PumpSeries/Index', [
            'filter_data' => $this->asFilterData([
                'brands' => PumpBrand::pluck('name')->all(),
                'categories' => PumpCategory::pluck('name')->all(),
                'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
                'applications' => PumpApplication::pluck('name')->all(),
                'types' => PumpType::pluck('name')->all(),
            ]),
            'brands' => PumpBrand::all(),
            'series' => PumpSeries::with(['brand', 'category', 'power_adjustment'])
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
                ->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('series_create');
        return Inertia::render('PumpSeries/Create', [
            'pump_series_props' => new PumpSeriesPropsResource(null),
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
        PumpSeries::createFromRequest($request);
        return Redirect::route('pump_series.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PumpSeries $pumpSeries
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(PumpSeries $pumpSeries): Response
    {
        $this->authorize('series_edit');
        return Inertia::render('PumpSeries/Edit', [
            'pump_series_props' => new PumpSeriesPropsResource(null),
            'series' => new PumpSeriesResource($pumpSeries)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PumpSeriesUpdateRequest $request
     * @param PumpSeries $pumpSeries
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(PumpSeriesUpdateRequest $request, PumpSeries $pumpSeries): RedirectResponse
    {
        $this->authorize('series_edit');
        $pumpSeries->updateFromRequest($request);
        return Redirect::route('pump_series.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PumpSeries $pumpSeries
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(PumpSeries $pumpSeries): RedirectResponse
    {
        $this->authorize('series_delete');
        $pumpSeries->delete();
        return Redirect::route('pump_series.index');

    }

    /*
     * Restore the specified resource
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('series_restore');
        PumpSeries::withTrashed()->find($id)->restore();
        return Redirect::route('pump_series.index');
    }
}
