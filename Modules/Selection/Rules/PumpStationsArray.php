<?php

namespace Modules\Selection\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\PumpStation;

final class PumpStationsArray implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws Exception
     */
    public function passes($attribute, $value): bool
    {
        foreach ($value as $pumpStation) {
            validator($pumpStation, [
                'id' => ['nullable', new In(PumpStation::pluck('id')->all())],
                'cost_price' => ['required', 'numeric', 'min:0'],
                'extra_percentage' => ['required', 'numeric'],
                'extra_sum' => ['required', 'numeric'],
                'final_price' => ['required', 'numeric'],
                'comment' => ['sometimes', 'nullable', 'string'],
                'main_pumps_count' => ['required', 'numeric', new In([1, 2, 3, 4, 5])],
                'reserve_pumps_count' => ['required', 'numeric', new In([0, 1, 2, 3, 4])],

                'pump_id' => ['required', new In(Pump::allOrCached()->pluck('id')->all())],
                'control_system_id' => ['required', new In(ControlSystem::allOrCached()->pluck('id')->all())],
                'chassis_id' => ['required', new In(Chassis::allOrCached()->pluck('id')->all())],
                'input_collector_id' => ['required', new In(Collector::allOrCached()->pluck('id')->all())],
                'output_collector_id' => ['required', new In(Collector::allOrCached()->pluck('id')->all())],
            ])->validate();
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public
    function message()
    {
        return 'Pump station has the wrong format';
    }
}
