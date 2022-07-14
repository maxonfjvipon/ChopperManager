<?php

namespace Modules\Selection\Http\Requests\WS\Auto;

use App\Rules\ArrayExistsInArray;
use Exception;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Rules\DnsMaterialsArray;

/**
 * Make WS auto selection request.
 */
class RqMakeWSAutoSelection extends RqMakeSelection
{
    /**
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'pump_series_ids' => ['required', 'array', new ArrayExistsInArray(PumpSeries::pluck('id')->all())],
                'deviation' => ['sometimes', 'nullable', 'numeric', 'min:-50', 'max:50'],
                'main_pumps_counts' => ['required', 'array', new ArrayExistsInArray([1, 2, 3, 4, 5])],
                'collectors' => ['required', 'array', new DnsMaterialsArray()],
            ]
        );
    }
}
