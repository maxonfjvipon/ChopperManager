<?php

namespace Modules\Project\Traits;

use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Entities\Selection;

/**
 * Project attributes.
 */
trait ProjectAttributes
{
    /**
     * @return array<string>
     */
    public function getAllPumpStationNamesAttribute(): array
    {
        return array_unique(
            array_merge(
                ...array_map(
                    fn(Selection $selection) => array_map(
                        fn(PumpStation $pumpStation) => mb_strtolower($pumpStation->full_name, 'utf-8'),
                        $selection->pump_stations->all()
                    ),
                    $this->selections->all()
                )
            )
        );
    }
}
