<?php


namespace Modules\PumpProducer\Services\Pump;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Services\PumpSeries\PumpSeriesService;
use Modules\PumpProducer\Entities\PPUser;

class PPPumpSeriesService extends PumpSeriesService
{
    public function store(PumpSeriesStoreRequest $request): RedirectResponse
    {
        $series = PumpSeries::createFromRequest($request);
        DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($userId) => [
            'user_id' => $userId, 'discountable_type' => 'pump_series', 'discountable_id' => $series->id
        ], PPUser::pluck('id')->all()));
        return Redirect::route('pump_series.index');
    }
}
