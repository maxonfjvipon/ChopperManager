<?php

namespace Modules\Selection\Support;

use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

/**
 * Selection component as {@Text}
 * @package Modules\Selection\Support
 */
final class TxtSelectionComponent implements Text
{
    public function __construct(
        private string $stationType,
        private string $selectionType
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return 'Selection::' . $this->stationType . $this->selectionType;
    }
}
