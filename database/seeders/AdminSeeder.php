<?php

namespace Database\Seeders;

use App\Models\users\User;
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
            'name' => "maxonfjvipon",
            'inn' => "1111111111",
            'email' => 'maxonfjvipon@admin.com',
            'password' => Hash::make('maximtrun19'),
            'phone' => "89991231212",
            'fio' => 'Трунников Максим Владиславович',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now()
        ]);

        User::create([
            'id' => 1,
            'name' => "Титарев&Co",
            'inn' => "1111111112",
            'email' => 'titatyov@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89991231212",
            'fio' => 'Титарев Дмитрий',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now()
        ]);

        User::create([
            'id' => 1,
            'name' => "Володин&Co",
            'inn' => "1111111113",
            'email' => 'volodin@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89991231212",
            'fio' => 'Володин Павел',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now()
        ]);
    }
}
