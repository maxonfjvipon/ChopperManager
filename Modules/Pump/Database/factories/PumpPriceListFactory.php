<?php

namespace Modules\Pump\Database\factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\Entities\Currency;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpsPriceList;
use Modules\User\Entities\Country;

class PumpPriceListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PumpsPriceList::class;

    /**
     * Define the model's default state.
     *
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'pump_id' => Pump::factory(),
            'country_id' => Country::allOrCached()->random()->id,
            'currency_id' => Currency::allOrCached()->random()->id,
            'price' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
