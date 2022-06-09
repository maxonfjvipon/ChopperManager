<?php

namespace Modules\Selection\Http\Requests\WaterAuto;

use App\Rules\ArrayExistsInArray;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\ControlSystemType;
use Modules\Pump\Entities\DN;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Http\Requests\RqMakeSelection;

final class RqMakeWaterAutoSelection extends RqMakeSelection
{
    /**
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'pump_series_ids' => ['required', 'array', new ArrayExistsInArray(PumpSeries::pluck('id')->all())],

            'flow' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'head' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'deviation' => ['sometimes', 'nullable', 'numeric', 'min:-50', 'max:50'],

            'main_pumps_counts' => ['required', 'array', new ArrayExistsInArray([1, 2, 3, 4, 5])],
            'reserve_pumps_count' => ['required', 'numeric', new In([0, 1, 2, 3, 4])],

            'collectors' => ['required', 'array', new ArrayExistsInArray(
                array_merge(...array_map(
                    fn($dn) => array_map(
                        fn($material) => "$dn $material",
                        CollectorMaterial::getDescriptions()
                    ),
                    DN::values()
                ))
            )],
            'control_system_type_ids' => ['required', 'array', new In(ControlSystemType::allOrCached()->pluck('id')->all())]
        ];
    }
}
