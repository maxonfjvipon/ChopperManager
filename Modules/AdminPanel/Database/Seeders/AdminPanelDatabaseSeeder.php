<?php

namespace Modules\AdminPanel\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\AdminPanel\Entities\Admin;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\TenantType;
use Modules\AdminPanel\Events\TenantCreated;
use Modules\Pump\Entities\Pump;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class AdminPanelDatabaseSeeder extends Seeder
{

    use UsesTenantModel;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pm = TenantType::create(['name' => 'PumpManager', 'guard' => 'pumpmanager']);
        $pd = TenantType::create(['name' => 'PumpProducer', 'guard' => 'pumpproducer']);

        $imgPath = "/img/selections-dashboard/";

        SelectionType::create(['name' => [
            'en' => 'Single pump selection',
            'ru' => 'Подбор одинарного насоса'
        ], 'pumpable_type' => Pump::$SINGLE_PUMP, 'default_img' => $imgPath . '01.png']);
        SelectionType::create(['name' => [
            'en' => 'Double pump selection',
            'ru' => 'Подбор сдвоенного насоса'
        ], 'pumpable_type' => Pump::$DOUBLE_PUMP, 'default_img' => $imgPath . '02.png']);
        SelectionType::create(['name' => [
            'en' => 'Water supply pumping station selection',
            'ru' => 'Подбор станции водоснбажения'
        ], 'pumpable_type' => Pump::$STATION_WATER, 'default_img' => $imgPath . '05.png']);
        SelectionType::create(['name' => [
            'en' => 'Fire extinguishing pumping station selection',
            'ru' => 'Подбор станции пожаротушения'
        ], 'pumpable_type' => Pump::$STATION_FIRE, 'default_img' => $imgPath . '06.png']);

        /* PUMP MANAGER TENANT */
        $pmt = $this->getTenantModel()::create([
            'name' => 'Pump Manager',
            'domain' => config('app.host'),
            'database' => 'pump_manager',
            'type_id' => $pm->id,
        ]);

        DB::table('tenants_and_selection_types')->insert(SelectionType::all()->map(fn($st) => [
            'tenant_id' => $pmt->id,
            'type_id' => $st->id,
            'img' => $st->default_img,
        ])->toArray());

        $pmt->execute(fn($tenant) => event(new TenantCreated($pmt)));

        // ADMIN
        Admin::create([
            'login' => 'maxonfjvipon',
            'password' => Hash::make('maximtrun19')
        ]);
    }
}
