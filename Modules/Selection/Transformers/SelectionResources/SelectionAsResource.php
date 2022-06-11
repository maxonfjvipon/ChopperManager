<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

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
        return (new ArrIf(
            !!$string,
            new ArrFromCallback(
                fn() => new ArrMapped(
                    ArrExploded::byComma($string),
                    'intval'
                )
            )
        ))->asArray();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (match ($this->selection->station_type->value) {
            StationType::WS => match ($this->selection->type->value) {
                SelectionType::Auto => new WaterAutoSelectionAsResource($this->selection),
                SelectionType::Handle => new WaterHandleSelectionAsResource($this->selection),
            },
        })->asArray();
    }
}
