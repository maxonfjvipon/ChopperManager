<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;

class TestingSeeder extends Seeder
{
    public function run()
    {
        $superAdmin = User::find(1);

        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $client = User::factory()->create();
        $client->assignRole('Client');

        PumpBrand::factory()->count(3)->create()->each(function (PumpBrand $brand) {
            $brand->series()->saveMany(PumpSeries::factory()->count(3)->make()->each(function (PumpSeries $series) {
                $series->pumps()->saveMany(Pump::factory()->count(2)->make());
            }));
        });

        collect([$superAdmin, $admin, $client])->each(function (User $user) {
            $user->projects()->saveMany(Project::factory()->count(3)->make()->each(function (Project $project) {
                $project->status_id = 1;
                $project->selections()->saveMany(Selection::factory()->count(2)->make());
            }));
        });


    }
}
