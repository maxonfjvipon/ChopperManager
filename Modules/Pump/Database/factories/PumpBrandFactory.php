<?php

namespace Modules\Pump\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PumpSeries\Entities\PumpBrand;

class PumpBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PumpBrand::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
