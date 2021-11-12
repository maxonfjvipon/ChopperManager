<?php

namespace Modules\PumpManager\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\PumpBrand;

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
        return Inertia::render('Pump::PumpSeries/Index', [
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
}
