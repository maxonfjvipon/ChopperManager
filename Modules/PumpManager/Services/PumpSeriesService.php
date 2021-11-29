<?php


namespace Modules\PumpManager\Services;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\PumpManager\Entities\User;

class PumpSeriesService extends \Modules\Pump\Services\PumpSeries\PumpSeriesService
{
    public function __index(): Response
    {
        return Inertia::render($this->indexPath(), [
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

    public function __store(PumpSeriesStoreRequest $request): RedirectResponse
    {
        User::addNewSeriesForSuperAdmins(PumpSeries::createFromRequest($request));
        return Redirect::route('pump_series.index');
    }
}
