<?php

namespace Modules\Selection\Http\Requests\AF\Handle;

use Modules\Selection\Http\Requests\WS\Handle\RqStoreWSHandleSelection;
use Modules\Selection\Traits\AFSelectionRequestHelpers;

final class RqStoreAFHandleSelection extends RqStoreWSHandleSelection
{
    use AFSelectionRequestHelpers;

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            $this->afSelectionRules()
        );
    }


    public function selectionProps(): array
    {
        return array_merge(
            parent::selectionProps(),
            $this->afSelectionProps()
        );
    }
}
