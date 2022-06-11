<?php

namespace Modules\Components\Actions;

use App\Support\ArrForFiltering;
use Illuminate\Support\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Components\Entities\ControlSystem;
use Modules\Components\Entities\ControlSystemType;
use Modules\Components\Entities\YesNo;
use Modules\Selection\Entities\MontageType;
use Modules\Selection\Entities\StationType;

final class AcControlSystems extends AcComponents
{
    public function __construct()
    {
        $controlSystems = ControlSystem::with('type')->get();
        parent::__construct(
            new ArrForFiltering([
                'pumps_counts' => [2, 3, 4, 5, 6],
                'montage_types' => MontageType::getDescriptions(),
                'gate_valves_counts' => [0, 1, 2],
                'yes_no' => YesNo::getDescriptions(),
            ]),
            'control_systems',
            ArrayableOf::items(
                self::stationTypeItems(
                    StationType::fromValue(StationType::WS),
                    $controlSystems,
                    fn(ControlSystem $controlSystem) => [
                        'id' => $controlSystem->id,
                        'power' => $controlSystem->power,
                        'pumps_count' => $controlSystem->pumps_count,
                        'weight' => $controlSystem->weight,
                        'price' => $controlSystem->price,
                        'currency' => $controlSystem->currency->key,
                        'price_updated_at' => date_format($controlSystem->price_updated_at, 'd.m.Y'),
                        'description' => $controlSystem->description,
                    ]
                ),
                self::stationTypeItems(
                    StationType::fromValue(StationType::AF),
                    $controlSystems,
                    fn(ControlSystem $controlSystem) => [
                        'id' => $controlSystem->id,
                        'power' => $controlSystem->power,
                        'pumps_count' => $controlSystem->pumps_count,
                        'avr' => $controlSystem->avr->description,
                        'gate_valves_count' => $controlSystem->gate_valves_count,
                        'kkv' => $controlSystem->kkv->description,
                        'on_street' => $controlSystem->on_street->description,
                        'has_jockey' => $controlSystem->has_jockey->description,
                        'montage_type' => $controlSystem->montage_type->description,
                        'weight' => $controlSystem->weight,
                        'price' => $controlSystem->price,
                        'currency' => $controlSystem->currency->key,
                        'price_updated_at' => date_format($controlSystem->price_updated_at, 'd.m.Y'),
                        'description' => $controlSystem->description
                    ]
                )
            )
        );
    }

    /**
     * @param StationType $type
     * @param Collection $controlSystems
     * @param callable $csCallback
     * @return array
     */
    public static function stationTypeItems(StationType $type, Collection $controlSystems, callable $csCallback): array
    {
        return [
            'station_type' => $type->description,
            'items' => array_values(
                array_map(
                    fn(ControlSystemType $controlSystemType) => [
                        'control_system_type' => $controlSystemType->name,
                        'items' => array_values(
                            array_map(
                                $csCallback,
                                $controlSystems->where('type.station_type.value', $type->value)
                                    ->where('type_id', $controlSystemType->id)
                                    ->all()
                            )
                        )
                    ],
                    $controlSystems->where('type.station_type.value', $type->value)
                        ->map(fn(ControlSystem $controlSystem) => $controlSystem->type)
                        ->unique('name')
                        ->all()
                )
            )
        ];
    }
}
