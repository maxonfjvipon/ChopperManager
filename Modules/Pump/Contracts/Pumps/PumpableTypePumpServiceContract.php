<?php

namespace Modules\Pump\Contracts\Pumps;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Pump\Entities\Pump;

interface PumpableTypePumpServiceContract
{
    public function pumpResource(Pump $pump): JsonResource;

    public function loadPumpResource(Pump $pump): array;

    public function queryPumps(): Builder;
}
