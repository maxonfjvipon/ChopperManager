<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\User\Entities\User;

class UsersSeeder extends Seeder
{
    /**
     * @throws \Exception
     */
    public function run()
    {
        $max = User::create([
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

        $vp = User::create([
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

        DB::table('users_and_selection_types')->insertOrIgnore(
            (new ArrMerged(
                new ArrMapped(
                    [1, 2, 3, 4],
                    fn($typeId) => ['user_id' => $max->id, 'type_id' => $typeId]
                ),
                new ArrMapped(
                    [1, 2, 3, 4],
                    fn($typeId) => ['user_id' => $vp->id, 'type_id' => $typeId]
                )
            ))->asArray()
        );
    }
}
