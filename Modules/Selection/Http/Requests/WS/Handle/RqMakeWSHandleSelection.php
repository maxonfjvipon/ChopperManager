<?php

namespace Modules\Selection\Http\Requests\WS\Handle;

use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Rules\DnMaterialRegex;

class RqMakeWSHandleSelection extends RqMakeSelection
{
    /**
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'pump_id' => ['required', new In(Pump::allOrCached()->pluck('id')->all())],
                'main_pumps_count' => ['required', new In([1, 2, 3, 4, 5])],
                'collector' => ['required', 'string', new DnMaterialRegex()],
            ]
        );
    }
}
