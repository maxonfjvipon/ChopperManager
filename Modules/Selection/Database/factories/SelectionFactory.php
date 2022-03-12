<?php
namespace Modules\Selection\Database\factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpSeries;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionRange;

class SelectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Selection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'project_id'                        => Project::factory(),
            'pump_id'                           => Pump::factory(),
            'selected_pump_name'                => $this->faker->text(),
            'pumps_count'                       => $this->faker->numberBetween(3, 9),
            'flow'                              => $this->faker->randomFloat(),
            'head'                              => $this->faker->randomFloat(),
            'fluid_temperature'                 => $this->faker->randomFloat(2, -30, 200),
            'deviation'                         => $this->faker->randomFloat(1, -50, 50),
            'reserve_pumps_count'               => $this->faker->numberBetween(1, 2),
            'range_id'                          => SelectionRange::allOrCached()->random()->id,
            'custom_range'                      => "0,100",
            'use_additional_filters'            => true,

            'power_limit_checked'               => true,
            'power_limit_condition_id'          => LimitCondition::allOrCached()->random()->id,
            'power_limit_value'                 => $this->faker->randomNumber(),

            'ptp_length_limit_checked'          => true,
            'ptp_length_limit_condition_id'     => LimitCondition::allOrCached()->random()->id,
            'ptp_length_limit_value'            => $this->faker->randomNumber(),

            'dn_suction_limit_checked'          => true,
            'dn_suction_limit_condition_id'     => LimitCondition::allOrCached()->random()->id,
            'dn_suction_limit_id'               => DN::allOrCached()->random()->id,

            'dn_pressure_limit_checked'         => true,
            'dn_pressure_limit_condition_id'    => LimitCondition::allOrCached()->random()->id,
            'dn_pressure_limit_id'              => DN::allOrCached()->random()->id,

            'dp_work_scheme_id'                 => DoublePumpWorkScheme::allOrCached()->random()->id,

            'connection_type_ids'               => "1,2",
            'mains_connection_ids'              => "1,2",
            'main_pumps_counts'                 => "1,2,3",
            'pump_brand_ids'                    => "1,2,3",
            'power_adjustment_ids'              => "1,2",
            'pump_type_ids'                     => "1,2",
            'pump_application_ids'              => "1,2",
            'pump_series_ids'                   => "1,2"
        ];
    }
}

