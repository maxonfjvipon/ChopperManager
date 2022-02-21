<?php
namespace Modules\Pump\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PumpBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Pump\Entities\PumpBrand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name
        ];
    }
}

