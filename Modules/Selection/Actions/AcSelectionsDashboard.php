<?php

namespace Modules\Selection\Actions;

use BenSampo\Enum\Enum;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

/**
 * Selections dashboard action.
 */
final class AcSelectionsDashboard extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(int $project_id)
    {
        parent::__construct(
            new ArrMerged(
                ['project_id' => $project_id],
                new ArrMapped(
                    [
                        'selection_types' => SelectionType::getInstances(),
                        'station_types' => StationType::getInstances(),
                    ],
                    fn (array $enum) => array_values(
                        array_map(
                            fn (Enum $type) => [
                                'description' => $type->description,
                                'key' => $type->key,
                            ],
                            $enum
                        )
                    )
                ),
            )
        );
    }
}
