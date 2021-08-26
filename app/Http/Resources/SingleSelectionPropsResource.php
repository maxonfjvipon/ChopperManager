<?php

namespace App\Http\Resources;

use App\Models\ConnectionType;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpRegulation;
use App\Models\Pumps\PumpType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleSelectionPropsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'producers' => PumpProducer::all(),
            'producersWithSeries' => PumpProducer::with(['series' => function ($query) {
                $query->orderBy('name');
            }, 'series.temperatures', 'series.types', 'series.applications', 'series.regulations'])
                ->get(),
            'types' => PumpType::all(),
            'connectionTypes' => ConnectionType::all(),
            'applications' => PumpApplication::all(),
            'phases' => CurrentPhase::all(),
            'dns' => DN::all(),
            'regulations' => PumpRegulation::all(),
            'limitConditions' => LimitCondition::all(),
            'defaults' => [
                'producers' => PumpProducer::whereName('Wilo')->pluck('id')->all(), // todo: default producers
                'regulations' => [PumpRegulation::firstWhere('id', 1)->id]
            ],
        ];
    }
}
