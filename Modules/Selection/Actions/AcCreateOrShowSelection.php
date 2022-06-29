<?php

namespace Modules\Selection\Actions;

use Auth;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\ControlSystemType;
use Modules\Components\Entities\YesNo;
use Modules\Pump\Entities\DN;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Transformers\SelectionResources\SelectionAsResource;

/**
 * Create or show selection action.
 */
final class AcCreateOrShowSelection extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param int $projectId
     * @param string $stationType
     * @param string $selectionType
     * @param Selection|null $selection
     * @throws Exception
     */
    public function __construct(
        private int        $projectId,
        private string     $stationType,
        private string     $selectionType,
        private ?Selection $selection = null
    )
    {
        parent::__construct(
            new ArrFromCallback(
                function () {
                    $series_ids = self::availableSeriesIds();
                    return new ArrMerged(
                        ['project_id' => $this->projectId],
                        new ArrObject(
                            "selection_props",
                            new ArrMerged(
                                [
                                    'control_system_types' => array_values(
                                        ControlSystemType::allOrCached()
                                            ->where('station_type.key', $this->stationType)
                                            ->all()
                                    ),
                                    'brands_with_series_with_pumps' => PumpBrand::with(
                                        array_merge([
                                            'series' => ($seriesCallback = function ($query) use ($series_ids) {
                                                $query->whereIn('id', $series_ids);
                                            }),
                                        ], $this->selectionType === SelectionType::getKey(SelectionType::Handle)
                                            ? ['series.pumps' => fn($query) => $query->select('id', 'series_id', 'name')]
                                            : [])
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
                                ],
                                new ArrIf(
                                    $this->stationType === StationType::getKey(StationType::AF),
                                    fn() => ['yes_no' => YesNo::asArrayForSelect()]
                                )
                            )
                        ),
                        [
                            'project_id' => $this->projectId,
                            'selection_type' => $this->selectionType,
                            'station_type' => $this->stationType
                        ],
                        new ArrIf(
                            !!$this->selection,
                            fn() => new ArrObject(
                                "selection",
                                new SelectionAsResource($this->selection)
                            )
                        ),
                    );
                }
            )
        );
    }

    /**
     * Get available {@see PumpSeries::$id}s for {@see Auth::user()}
     * @return array
     */
    private static function availableSeriesIds(): array
    {
        return Auth::user()->available_series()
            ->notDiscontinued()
            ->pluck('id')
            ->all();
    }
}
