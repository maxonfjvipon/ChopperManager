<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Transformers\RcPumpStation;

class SelectionAsResource implements Arrayable
{
    /**
     * Ctor.
     * @param Selection $selection
     */
    public function __construct(protected Selection $selection)
    {
    }

    /**
     * @throws Exception
     */
    protected function intsArrayFromString($string): array
    {
        return ArrIf::new(
            !!$string,
            fn() => new ArrMapped(
                ArrExploded::byComma($string),
                'intval'
            )
        )->asArray();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMerged(
            [
                'id' => $this->selection->id,
                'flow' => $this->selection->flow,
                'head' => $this->selection->head,
                'control_system_type_ids' => $this->intsArrayFromString($this->selection->control_system_type_ids),
                'comment' => $this->selection->comment,
                'pump_stations' => RcPumpStation::collection($this->selection->pump_stations_to_show())
            ],
            match ($this->selection->station_type->value) {
                StationType::WS => match ($this->selection->type->value) {
                    SelectionType::Auto => new WSAutoSelectionAsResource($this->selection),
                    SelectionType::Handle => new WSHandleSelectionAsResource($this->selection),
                },
                StationType::AF => match ($this->selection->type->value) {
                    SelectionType::Auto => new AFAutoSelectionAsResource($this->selection)
                }
            }
        ))->asArray();
    }
}
