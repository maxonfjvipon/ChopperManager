<?php


namespace Modules\PumpManager\Services\Pump;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Services\PumpSeries\PumpSeriesService;
use Modules\PumpManager\Entities\PMUser;

class PMPumpSeriesService extends PumpSeriesService
{
    public function store(PumpSeriesStoreRequest $request): RedirectResponse
    {
        PMUser::addNewSeriesForSuperAdmins(PumpSeries::createFromRequest($request));
        return Redirect::route('pump_series.index');
    }
}
