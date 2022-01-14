<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class AdminSeeder extends Seeder
{
    use UsesTenantModel;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    }
}
