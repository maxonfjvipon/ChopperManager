<?php

namespace Modules\Selection\Actions;

use Auth;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\ControlSystemType;
use Modules\Components\Entities\YesNo;
use Modules\Pump\Entities\DN;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Transformers\SelectionResources\SelectionAsResource;

final class AcCreateOrShowSelection
{
    /**
     * @param int $project_id
     * @param string $stationType
     * @param string $selectionType
     * @param Selection|null $selection
     * @return array
     * @throws Exception
     */
    public function __invoke(int $project_id, string $stationType, string $selectionType, Selection $selection = null): array
    {
        $series_ids = Auth::user()->available_series()
            ->notDiscontinued()
            ->pluck('id')
            ->all();
        $seriesCallback = function ($query) use ($series_ids) {
            $query->whereIn('id', $series_ids);
        };
        return (new ArrMerged(
            [
                'project_id' => $project_id,
                'selection_props' => array_merge([
                    'control_system_types' => array_values(
                        ControlSystemType::allOrCached()
                            ->where('station_type.key', $stationType)
                            ->all()
                    ),
                    'brands_with_series_with_pumps' => PumpBrand::with(
                        array_merge([
                            'series' => $seriesCallback,
                        ], $selectionType === SelectionType::getKey(SelectionType::Handle)
                            ? ['series.pumps' => function ($query) {
                                $query->select('id', 'series_id', 'name');
                            }] : [])
                    )
                        ->whereHas('series', $seriesCallback)
                        ->get(),
                    'collectors' => array_merge(...array_map(
                        fn($dn) => array_map(
                            fn($material) => "$dn $material",
                            CollectorMaterial::getDescriptions()
                        ),
                        DN::values()
                    )),
                ], $stationType === StationType::getKey(StationType::AF) ? [
                    'yes_no' => YesNo::asArrayForSelect()
                ] : []
                ),
                'selection_type' => $selectionType,
                'station_type' => $stationType
            ],
            new ArrIf(
                !!$selection,
                fn() => new ArrObject(
                    'selection',
                    new SelectionAsResource($selection)
                )
            )
        ))->asArray();
    }
}
