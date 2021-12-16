<?php


namespace Modules\PumpProducer\Services\Pump;

use Inertia\Response;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;
use Modules\Pump\Services\Pumps\PumpsService;

class PPPumpsService extends PumpsService
{
    /**
     * @return array
     */
    protected function pumpsFilterDataResource(): array
    {
        return [
            'brands' => PumpBrand::pluck('name')->all(),
            'series' => PumpSeries::pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'connections' => ConnectionType::pluck('name')->all(),
            'dns' => DN::pluck('value')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
            'types' => PumpType::pluck('name')->all(),
            'applications' => PumpApplication::pluck('name')->all(),
        ];
    }

    protected function loadedPumps(): array
    {
        // TODO: Implement loadedPumps() method.
    }

    public function show(Pump $pump): Response
    {
        // TODO: Implement show() method.
    }
}
