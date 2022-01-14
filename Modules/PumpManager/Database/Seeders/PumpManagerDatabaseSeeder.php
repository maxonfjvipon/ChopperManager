<?php

namespace Modules\PumpManager\Database\Seeders;

use Database\Seeders\AdminSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\PMUser;

class PumpManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('module:seed', [
            'module' => 'Core',
            '--force' => true,
            '--database' => 'tenant',
        ]);
        $max = PMUser::create([
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
        $vp = PMUser::create([
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
        $seriesIds = PumpSeries::pluck('id')->all();
        $tenantSelectionTypeIds = Tenant::current()->selection_types()->pluck('id')->all();
        $pumpBrandIds = PumpBrand::pluck('id')->all();
        foreach ([$max, $vp] as $user) {
            DB::table(Tenant::current()->database . '.users_and_pump_series')->insert(array_map(fn($seriesId) => [
                'user_id' => $user->id, 'series_id' => $seriesId
            ], $seriesIds));
            DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($seriesId) => [
                'user_id' => $user->id, 'discountable_type' => 'pump_series', 'discountable_id' => $seriesId
            ], $seriesIds));
            DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($brandId) => [
                'user_id' => $user->id, 'discountable_type' => 'pump_brand', 'discountable_id' => $brandId
            ], $pumpBrandIds));
            DB::table(Tenant::current()->database . '.users_and_selection_types')->insert(array_map(fn($selectionTypeId) => [
                'user_id' => $user->id, 'type_id' => $selectionTypeId
            ], $tenantSelectionTypeIds));
        }
    }
}
