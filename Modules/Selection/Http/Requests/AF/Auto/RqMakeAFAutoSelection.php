<?php

namespace Modules\Selection\Http\Requests\AF\Auto;

use Exception;
use Modules\Selection\Http\Requests\WS\Auto\RqMakeWSAutoSelection;
use Modules\Selection\Traits\AFSelectionRequestHelpers;

final class RqMakeAFAutoSelection extends RqMakeWSAutoSelection
{
    use AFSelectionRequestHelpers;

    /**
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            $this->afSelectionRules()
        );
    }
}
