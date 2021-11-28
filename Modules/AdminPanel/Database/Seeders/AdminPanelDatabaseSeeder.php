<?php

namespace Modules\AdminPanel\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\AdminPanel\Entities\Admin;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\TenantType;
use Modules\AdminPanel\Events\TenantCreated;
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
        $pm = TenantType::create(['name' => 'PumpManager', 'module_name' => 'pumpmanager']);
        $pd = TenantType::create(['name' => 'PumpProducer', 'module_name' => 'pumpproducer']);

        $imgPath = "/img/selections-dashboard/";

        SelectionType::create(['name' => [
            'en' => 'Single pump selection',
            'ru' => 'Подбор одинарного насоса'
        ], 'prefix' => 'sp', 'default_img' => $imgPath . '01.png']);
        SelectionType::create(['name' => [
            'en' => 'Double pump selection',
            'ru' => 'Подбор сдвоенного насоса'
        ], 'prefix' => 'dp', 'default_img' => $imgPath . '02.png']);
        SelectionType::create(['name' => [
            'en' => 'Water supply pumping station selection',
            'ru' => 'Подбор станции водоснбажения'
        ], 'prefix' => 'sw', 'default_img' => $imgPath . '05.png']);
        SelectionType::create(['name' => [
            'en' => 'Fire extinguishing pumping station selection',
            'ru' => 'Подбор станции пожаротушения'
        ], 'prefix' => 'sf', 'default_img' => $imgPath . '06.png']);

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

//        DB::table('tenants_and_selection_types')->insert(DB::table('selection_types')->get()->map(fn($st) => [
//            'tenant_id' => $pmt->id,
//            'type_id' => $st->id,
//            'img' => $st->default_img,
//        ])->toArray());

        // ADMIN
        Admin::create([
            'login' => 'maxonfjvipon',
            'password' => Hash::make('maximtrun19')
        ]);
    }
}
