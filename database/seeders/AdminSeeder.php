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
            'inn' => "1111111111",
            'email' => 'maxonfjvipon@admin.com',
            'password' => Hash::make('maximtrun19'),
            'phone' => "89991231212",
            'first_name' => 'Максим',
            'middle_name' => 'Трунников',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        User::create([
            'id' => 2,
            'organization_name' => "МБС",
            'inn' => "1111111112",
            'email' => 'titatyov@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89991231212",
            'first_name' => 'Дмитрий',
            'middle_name' => 'Титарев',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        User::create([
            'id' => 3,
            'organization_name' => "МБС",
            'inn' => "1111111113",
            'email' => 'volodin@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89991231212",
            'first_name' => 'Павел',
            'middle_name' => 'Володин',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);
    }
}
