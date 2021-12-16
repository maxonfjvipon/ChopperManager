<?php


namespace Modules\Pump\Services\Pumps\PumpType;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Pump\Entities\Pump;

class DoublePumpService extends PumpableTypePumpService
{

    public function pumpResource(Pump $pump): JsonResource
    {
        // TODO: Implement pumpResource() method.
    }

    public function loadPumpResource(Pump $pump): array
    {
        // TODO: Implement loadPumpResource() method.
    }

    public function queryPumps(): Builder
    {
        // TODO: Implement queryPumps() method.
    }
}
