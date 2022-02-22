<?php

namespace Modules\User\Database\factories;

use Exception;
use Modules\Project\Entities\Currency;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'organization_name' => $this->faker->name(),
            'itn' => $this->faker->text(11),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone' => '89991231212',
            'first_name' => $this->faker->firstNameMale(),
            'middle_name' => $this->faker->lastName(),
            'city' => 'Bryansk',
            'postcode' => "123123",
            'business_id' => Business::allOrCached()->random()->id,
            'country_id' => Country::allOrCached()->random()->id,
            'currency_id' => Currency::allOrCached()->random()->id,
            'is_active' => true,
            'last_login_at' => now()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
        });
    }
}
