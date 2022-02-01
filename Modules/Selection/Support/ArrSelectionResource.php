<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Transformers\SelectionResources\DoublePumpSelectionResource;
use Modules\Selection\Transformers\SelectionResources\SinglePumpSelectionResource;

/**
 * Selection resource as arrayable.
 * @package Modules\Selection\Support
 */
class ArrSelectionResource implements Arrayable
{
    /**
     * @var Selection
     */
    private Selection $selection;

    /**
     * @param Selection $selection
     * @return ArrSelectionResource
     */
    public static function new(Selection $selection): ArrSelectionResource
    {
        return new self($selection);
    }

    /**
     * Ctor.
     * @param Selection $selection
     */
    public function __construct(Selection $selection)
    {
        $this->selection = $selection;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return ['selection' => match ($this->selection->pump_type) {
            Pump::$DOUBLE_PUMP => (new DoublePumpSelectionResource($this->selection)),
            Pump::$SINGLE_PUMP => (new SinglePumpSelectionResource($this->selection))
        }];
    }
}
