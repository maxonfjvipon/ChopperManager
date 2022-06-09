<?php

namespace Modules\Selection\Actions;

use Auth;
use Exception;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\ControlSystemType;
use Modules\Pump\Entities\DN;
use Modules\PumpSeries\Entities\PumpBrand;

final class AcCreateSelection
{
    /**
     * @param int $project_id
     * @param string $stationType
     * @param string $selectionType
     * @return array
     * @throws Exception
     */
    public function __invoke(int $project_id, string $stationType, string $selectionType): array
    {
        $series_ids = Auth::user()->available_series()
            ->notDiscontinued()
            ->pluck('id')
            ->all();
        $seriesCallback = function ($query) use ($series_ids) {
            $query->whereIn('id', $series_ids);
        };
        return [
            'project_id' => $project_id,
            'selection_props' => [
                'control_system_types' => array_values(
                    ControlSystemType::allOrCached()
                        ->where('station_type.key', $stationType)
                        ->all()
                ),
                'brands_with_series_with_pumps' => PumpBrand::with([
                    'series' => $seriesCallback,
                    'series.pumps' => function ($query) {
                        $query->select('id', 'series_id', 'name');
                    }
                ])
                    ->whereHas('series', $seriesCallback)
                    ->get(),
                'collectors' => array_merge(...array_map(
                    fn($dn) => array_map(
                        fn($material) => "$dn $material",
                        CollectorMaterial::getDescriptions()
                    ),
                    DN::values()
                ))
            ]
        ];
    }
}
