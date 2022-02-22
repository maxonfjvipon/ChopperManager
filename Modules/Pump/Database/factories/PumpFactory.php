<?php

namespace Modules\Pump\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpSeries;

class PumpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Pump\Entities\Pump::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_num_main' => $this->faker->text(40),
            'article_num_reserve' => $this->faker->text(),
            'article_num_archive' => $this->faker->text(),
            'series_id' => PumpSeries::factory(),
            'name' => $this->faker->name(),
            'weight' => $this->faker->randomNumber(),
            'rated_power' => $this->faker->randomNumber(),
            'rated_current' => $this->faker->randomNumber(),
            'connection_type_id' => ConnectionType::allOrCached()->random()->id,
            'fluid_temp_min' => $this->faker->randomNumber(),
            'fluid_temp_max' => $this->faker->randomNumber(),
            'ptp_length' => $this->faker->randomNumber(),
            'dn_suction_id' => DN::allOrCached()->random()->id,
            'dn_pressure_id' => DN::allOrCached()->random()->id,
            'connection_id' => MainsConnection::allOrCached()->random()->id,
            'sp_performance' => '0 5.2 1 4.78 1.6 4.42 2.2 3.97 3 3.28 3.6 2.6 4 2.13 4.8 1.16 5.44 0.37',
            'pumpable_type' => $this->faker->randomElement([Pump::$SINGLE_PUMP, Pump::$DOUBLE_PUMP]),
            'dp_standby_performance' => '0.000 4.195 1.382 4.054 4.137 3.742 6.634 3.435 8.987 3.125 11.168 2.814 13.205 2.507 15.128 2.196 16.935 1.889 18.686 1.576 20.322 1.268 21.842 0.964 23.249 0.660 23.937 0.498',
            'dp_peak_performance' => '0.000 4.202 2.714 4.086 8.981 3.749 15.249 3.379 21.455 2.989 27.231 2.586 32.515 2.178 37.492 1.762 42.162 1.354 46.648 0.949 50.089 0.626 51.381 0.496',
        ];
    }
}

