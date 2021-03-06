<?php

namespace Modules\Selection\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\ValidationException;
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
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @throws Exception
     */
    public function passes($attribute, $value): bool
    {
        try {
            foreach ($value as $pumpStation) {
                validator($pumpStation, [
                    'id' => ['nullable', new In(PumpStation::pluck('id')->all())],
                    'full_name' => ['required', 'string', 'max:255'],
                    'cost_price' => ['required', 'numeric', 'min:0'],
                    'extra_percentage' => ['required', 'numeric'],
                    'extra_sum' => ['required', 'numeric'],
                    'final_price' => ['required', 'numeric'],
                    'comment' => ['sometimes', 'nullable', 'string'],
                    'main_pumps_count' => ['required', 'numeric', new In([1, 2, 3, 4, 5])],
                    'reserve_pumps_count' => ['required', 'numeric', new In([0, 1, 2, 3, 4])],

                    'pump_id' => ['required', new In($pumpIds = Pump::allOrCached()->pluck('id')->all())],
                    'control_system_id' => ['required', new In(ControlSystem::allOrCached()->pluck('id')->all())],
                    'chassis_id' => ['required', new In($chassisIds = Chassis::allOrCached()->pluck('id')->all())],
                    'input_collector_id' => ['required', new In(Collector::allOrCached()->pluck('id')->all())],
                    'output_collector_id' => ['required', new In(Collector::allOrCached()->pluck('id')->all())],

                    'jockey_pump_id' => ['sometimes', 'nullable', new In($pumpIds)],
                    'jockey_chassis_id' => ['sometimes', 'nullable', new In($chassisIds)],
                    'jockey_flow' => ['sometimes', 'nullable', 'numeric', 'min:0'],
                    'jockey_head' => ['sometimes', 'nullable', 'numeric', 'min:0'],
                ])->validate();
            }
        } catch (ValidationException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'Pump station has the wrong format';
    }
}
