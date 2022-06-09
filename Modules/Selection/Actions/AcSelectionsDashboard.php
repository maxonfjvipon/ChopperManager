<?php

namespace Modules\Selection\Actions;

use App\Support\ArrForFiltering;
use BenSampo\Enum\Enum;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

final class AcSelectionsDashboard
{
    public function __invoke(int $project_id): Arrayable
    {
        return new ArrMerged(
            ['project_id' => $project_id],
            new ArrMapped(
                [
                    'selection_types' => SelectionType::getInstances(),
                    'station_types' => StationType::getInstances()
                ],
                fn(array $enum) => array_values(
                    array_map(
                        fn(Enum $type) => [
                            'description' => $type->description,
                            'key' => $type->key
                        ],
                        $enum
                    )
                )

            ),
        );
    }
}
