<?php

namespace Modules\Pump\Database\factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PumpSeries\Entities\ElPowerAdjustment;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpCategory;

class PumpSeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\PumpSeries\Entities\PumpSeries::class;

    /**
     * Define the model's default state.
     *
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'brand_id' => PumpBrand::factory(),
            'power_adjustment_id' => ElPowerAdjustment::allOrCached()->random()->id,
            'category_id' => PumpCategory::allOrCached()->random()->id,
            'temps_min' => '0,20',
            'temps_max' => '100,110',
            'image' => $this->faker->text,
            'is_discontinued' => false,
        ];
    }
}
