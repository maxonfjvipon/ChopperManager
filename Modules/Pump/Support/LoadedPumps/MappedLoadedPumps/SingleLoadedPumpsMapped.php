<?php

namespace Modules\Pump\Support\LoadedPumps\MappedLoadedPumps;


use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;

/**
 * Single loaded pumps mapped
 */
final class SingleLoadedPumpsMapped implements Arrayable
{
    /**
     * @var Arrayable $pumps
     */
    private Arrayable $pumps;

    /**
     * Ctor.
     * @param Arrayable $pumps
     */
    public function __construct(Arrayable $pumps)
    {
        $this->pumps = $pumps;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMapped(
            $this->pumps,
            fn(Pump $pump) => [
                'id' => $pump->id,
                'article_num_main' => $pump->article_num_main,
                'article_num_archive' => $pump->article_num_archive,
                'brand' => $pump->series->brand->name,
                'series' => $pump->series->name,
                'name' => $pump->name,
                'weight' => $pump->weight,
                'price' => $pump->priceListForCurrentUser() ? round($pump->priceListForCurrentUser()->price, 2) : null,
                'currency' => $pump->priceListForCurrentUser()->currency->code ?? null,
                'connection_type' => $pump->connection_type->name,
                'rated_power' => $pump->rated_power,
                'rated_current' => $pump->rated_current,
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
                'is_discontinued' => $pump->is_discontinued_with_series,
            ]
        ))->asArray();
    }
}