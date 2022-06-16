<?php

namespace Modules\Selection\Http\Requests\AF\Auto;

use App\Rules\ArrayExistsInArray;
use Exception;
use Modules\PumpSeries\Entities\PumpSeries;
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
            $this->afSelectionRules(),
        );
    }
}
