<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class DatabaseSeeder extends Seeder
{
    use UsesTenantModel;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->getTenantModel()::checkCurrent()
            ? (new TenantSpecificSeeder())->run()
            : (new LandlordSpecificSeeder())->run();
    }
}
