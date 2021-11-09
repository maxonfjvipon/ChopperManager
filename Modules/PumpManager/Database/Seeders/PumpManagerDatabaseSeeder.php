<?php

namespace Modules\PumpManager\Database\Seeders;

use Database\Seeders\AdminSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\User;

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
        (new AdminSeeder())->run();
        $users = User::all();
        foreach ($users as $user) {
            $seriesIds = PumpSeries::pluck('id')->all();
            DB::table(Tenant::current()->database . '.users_and_pump_series')->insert(array_map(fn($seriesId) => [
                'user_id' => $user->id, 'series_id' => $seriesId
            ], $seriesIds));
            DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($seriesId) => [
                'user_id' => $user->id, 'discountable_type' => 'pump_series', 'discountable_id' => $seriesId
            ], $seriesIds));
            DB::table(Tenant::current()->database . '.discounts')->insert(array_map(fn($brandId) => [
                'user_id' => $user->id, 'discountable_type' => 'pump_brand', 'discountable_id' => $brandId
            ], PumpBrand::pluck('id')->all()));
            DB::table(Tenant::current()->database . '.users_and_selection_types')->insert(array_map(fn($selectionTypeId) => [
                'user_id' => $user->id, 'type_id' => $selectionTypeId
            ], Tenant::current()->selection_types()->pluck('id')->all()));
        }
    }
}
