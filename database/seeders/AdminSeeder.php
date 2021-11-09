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
        $userModel = new ($this->getTenantModel()::current()->getUserClass());
        $max = $userModel::create([
            'id' => 1,
            'organization_name' => "МБС",
            'itn' => "0000000000",
            'email' => 'maxonfjvipon@admin.com',
            'password' => Hash::make('maximtrun19'),
            'phone' => "89991231212",
            'first_name' => 'Максим',
            'middle_name' => 'Трунников',
            'city' => "Брянск",
            'business_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now(),
            'country_id' => 1,
            'currency_id' => 121
        ]);
        $max->assignRole('SuperAdmin');

        $dt = $userModel::create([
            'id' => 2,
            'organization_name' => "МБС",
            'itn' => "0000000001",
            'email' => 'titaryov@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89991231212",
            'first_name' => 'Дмитрий',
            'middle_name' => 'Титарев',
            'city' => "Брянск",
            'business_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now(),
            'country_id' => 1,
            'currency_id' => 121
        ]);
        $dt->assignRole('SuperAdmin');

        $vp = $userModel::create([
            'id' => 3,
            'organization_name' => "МБС",
            'itn' => "0000000002",
            'email' => 'volodin@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89991231212",
            'first_name' => 'Павел',
            'middle_name' => 'Володин',
            'city' => "Брянск",
            'business_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now(),
            'country_id' => 1,
            'currency_id' => 121
        ]);
        $vp->assignRole('SuperAdmin');
    }
}
