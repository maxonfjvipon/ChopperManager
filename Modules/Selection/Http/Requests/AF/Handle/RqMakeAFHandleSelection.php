<?php

namespace Modules\Selection\Http\Requests\AF\Handle;

use Modules\Selection\Http\Requests\WS\Handle\RqMakeWSHandleSelection;
use Modules\Selection\Traits\AFSelectionRequestHelpers;

final class RqMakeAFHandleSelection extends RqMakeWSHandleSelection
{
    use AFSelectionRequestHelpers;

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            $this->afSelectionRules(),
        );
    }
}
