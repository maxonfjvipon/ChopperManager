<?php

namespace Modules\PumpManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\PumpManager\Entities\User;

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
        return Inertia::render($this->indexPath, [
            'filter_data' => $this->indexFilterData(),
            'brands' => Auth::user()->available_brands,
            'series' => Auth::user()->available_series()->with(['brand', 'category', 'power_adjustment'])
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
                ->all()
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
        User::addNewSeriesForSuperAdmins(PumpSeries::createFromRequest($request));
        return Redirect::route('pump_series.index');
    }
}
