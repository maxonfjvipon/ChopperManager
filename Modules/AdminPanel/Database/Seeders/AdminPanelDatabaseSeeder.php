<?php

namespace Modules\AdminPanel\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\AdminPanel\Entities\Admin;
use Modules\AdminPanel\Entities\TenantType;
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

        $pm = TenantType::create(['name' => 'PumpManager']);
        $pd = TenantType::create(['name' => 'PumpProducer']);

        /* PUMP MANAGER TENANT */
        $this->getTenantModel()::create([
            'name' => 'Pump Manager',
            'domain' => 'localhost',
            'database' => 'pump_manager',
            'type_id' => $pm->id,
        ]);

        // ADMIN
        Admin::create([
            'login' => 'maxonfjvipon',
            'password' => Hash::make('maximtrun19')
        ]);
    }
}
