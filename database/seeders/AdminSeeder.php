<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
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

        User::create([
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

        User::create([
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
    }
}
