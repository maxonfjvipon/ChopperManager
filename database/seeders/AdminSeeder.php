<?php

namespace Database\Seeders;

use App\Models\users\User;
use Brick\Math\BigInteger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Type\Integer;

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
            'id' => 999,
            'name' => "admin",
            'inn' => "1234567890",
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'phone' => "89101235412",
            'fio' => 'admin',
            'role_id' => 1,
            'city_id' => 1,
            'business_id' => 1,
            'created_at' => now()
        ]);
    }
}
