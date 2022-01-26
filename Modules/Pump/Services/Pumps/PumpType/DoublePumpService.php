<?php


namespace Modules\Pump\Services\Pumps\PumpType;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Transformers\Pumps\DoublePumpResource;

class DoublePumpService extends PumpableTypePumpService
{

    #[Pure] public function pumpResource(Pump $pump): JsonResource
    {
        return new DoublePumpResource($pump);
    }

    public function loadPumpResource(Pump $pump): array
    {
        return [
            'id' => $pump->id,
            'article_num_main' => $pump->article_num_main,
            'article_num_reserve' => $pump->article_num_reserve,
            'article_num_archive' => $pump->article_num_archive,
            'brand' => $pump->series->brand->name,
            'series' => $pump->series->name,
            'name' => $pump->name,
            'weight' => $pump->weight,
            'price' => $pump->price_list ? round($pump->price_list->price, 2) : null,
            'currency' => $pump->price_list->currency->code ?? null,
            'rated_power' => $pump->rated_power,
            'rated_current' => $pump->rated_current,
            'connection_type' => $pump->connection_type->name,
            'fluid_temp_min' => $pump->fluid_temp_min,
            'fluid_temp_max' => $pump->fluid_temp_max,
            'ptp_length' => $pump->ptp_length,
            'dn_suction' => $pump->dn_suction->value,
            'dn_pressure' => $pump->dn_pressure->value,
            'category' => $pump->series->category->name,
            'power_adjustment' => $pump->series->power_adjustment->name,
            'mains_connection' => $pump->mains_connection->full_value,
            'applications' => $pump->applications,
            'types' => $pump->types,
            'pumpable_type' => $pump->pumpable_type,
            'is_discontinued' => $pump->is_discountinued,
        ];
    }

    protected function pumpsQuery(): Builder
    {
        return Pump::with([
            'series',
            'series.brand',
            'series.power_adjustment',
            'series.category',
            'series.applications',
            'series.types'
        ])
            ->with('mains_connection')
            ->with('dn_suction')
            ->with('dn_pressure')
            ->with('connection_type')
            ->with(['price_list', 'price_list.currency'])
            ->doublePumps();
    }
}
