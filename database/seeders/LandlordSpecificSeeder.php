<?php


namespace Database\Seeders;


use Illuminate\Support\Facades\Hash;
use Modules\AdminPanel\Entities\Admin;
use Modules\AdminPanel\Entities\TenantType;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class LandlordSpecificSeeder
{
    use UsesTenantModel;

    public function run()
    {
        $pm = TenantType::create(['name' => 'PumpManager']);
        $pd = TenantType::create(['name' => 'PumpProducer']);

        /* PUMP MANAGER TENANT */
        $this->getTenantModel()::create([
            'name' => 'Pump Manager',
            'domain' => 'localhost',
            'database' => 'tenant1',
            'type_id' => $pm->id,
        ]);

        // ADMIN
        Admin::create([
            'login' => 'maxonfjvipon',
            'password' => Hash::make('maximtrun19')
        ]);
    }
}
