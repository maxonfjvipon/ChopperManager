<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Database\Seeders\TenantSpecificSeeder;
use Modules\PumpManager\Entities\User;
use Modules\User\Entities\Role;
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
        Tenant::firstWhere('name', 'Pump Manager')->execute(function (Tenant $tenant) {
            $tula = User::create([
                'organization_name' => "ОВК Тула",
                'itn' => "0000000007",
                'email' => 'tula@test.com',
                'password' => Hash::make('qwertyuiop'),
                'phone' => "89991231212",
                'first_name' => 'Test',
                'middle_name' => 'Test',
                'city' => "Тула",
                'business_id' => 1,
                'created_at' => now(),
                'email_verified_at' => now(),
                'country_id' => 1,
                'currency_id' => 121
            ]);
            $helios = User::create([
                'organization_name' => "Гелиос Воронеж",
                'itn' => "0000000008",
                'email' => 'helios@test.com',
                'password' => Hash::make('qwertyuiop'),
                'phone' => "89991231212",
                'first_name' => 'Test',
                'middle_name' => 'Test',
                'city' => "Воронеж",
                'business_id' => 1,
                'created_at' => now(),
                'email_verified_at' => now(),
                'country_id' => 1,
                'currency_id' => 121
            ]);
            $tula->assignRole('Client');
            $helios->assignRole('Client');
        });

    }
}
