<?php

namespace Modules\Project\Database\factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectDeliveryStatus;
use Modules\Project\Entities\ProjectStatus;
use Modules\User\Entities\User;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'user_id' => User::all()->random()->id,
            'status_id' => ProjectStatus::allOrCached()->random()->id,
            'delivery_status_id' => ProjectDeliveryStatus::allOrCached()->random()->id,
            'comment' => $this->faker->text(),
        ];
    }
}

