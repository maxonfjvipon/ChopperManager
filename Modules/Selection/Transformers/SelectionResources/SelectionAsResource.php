<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Transformers\RcPumpStation;

/**
 * Selection as resource.
 */
final class SelectionAsResource extends ArrEnvelope
{
    use HasIdsArrayFromString;

    /**
     * Ctor.
     *
     * @param Selection $selection
     *
     * @throws Exception
     */
    public function __construct(protected Selection $selection)
    {
        parent::__construct(
            new ArrMerged(
                [
                    'id' => $this->selection->id,
                    'flow' => $this->selection->flow,
                    'head' => $this->selection->head,
                    'control_system_type_ids' => $this->idsArrayFromString($this->selection->control_system_type_ids),
                    'comment' => $this->selection->comment,
                    'pump_stations' => RcPumpStation::collection($this->selection->pump_stations_to_show()),
                ],
                match ($this->selection->station_type->value) {
                    StationType::WS => match ($this->selection->type->value) {
                        SelectionType::Auto => new WSAutoSelectionAsResource($this->selection),
                        SelectionType::Handle => new WSHandleSelectionAsResource($this->selection),
                    },
                    StationType::AF => match ($this->selection->type->value) {
                        SelectionType::Auto => new AFAutoSelectionAsResource($this->selection),
                        SelectionType::Handle => new AFHandleSelectionAsResource($this->selection)
                    }
                }
            )
        );
    }
}
