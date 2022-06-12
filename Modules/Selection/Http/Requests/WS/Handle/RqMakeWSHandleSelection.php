<?php

namespace Modules\Selection\Http\Requests\WS\Handle;

use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\ControlSystemType;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Rules\DnMaterialRegex;

final class RqMakeWSHandleSelection extends RqMakeSelection
{
    /**
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'pump_id' => ['required', new In(Pump::allOrCached()->pluck('id')->all())],

            'flow' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'head' => ['required', 'numeric', 'min:0', 'not_in:0'],

            'main_pumps_count' => ['required', new In([1, 2, 3, 4, 5])],
            'reserve_pumps_count' => ['required', new In([0, 1, 2, 3, 4])],

            'collector' => ['required', 'string', new DnMaterialRegex()],
            'control_system_type_ids' => ['required', 'array', new In(ControlSystemType::allOrCached()->pluck('id')->all())]
        ];
    }
}
