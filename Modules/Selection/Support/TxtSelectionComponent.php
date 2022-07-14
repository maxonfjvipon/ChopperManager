<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Text;

/**
 * Selection component as {@Text}.
 */
final class TxtSelectionComponent implements Text
{
    /**
     * Ctor.
     *
     * @param string $stationType
     * @param string $selectionType
     */
    public function __construct(
        private string $stationType,
        private string $selectionType
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function asString(): string
    {
        return 'Selection::'.$this->stationType.$this->selectionType;
    }
}
