<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Database\Seeders\TenantSpecificSeeder;
use Modules\User\Entities\Role;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class DatabaseSeeder extends Seeder
{
    use UsesTenantModel;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'project_export',
            'selection_export',

        ];
        Tenant::all()->eachCurrent(function (Tenant $tenant) use ($permissions) {
            $tenantGuard = $tenant->getGuard();
            $clientRole = Role::findByName('Client', $tenantGuard);
            $adminRole = Role::findByName('Admin', $tenantGuard);
            foreach ($permissions as $permission) {
                $clientRole->givePermissionTo($permission);
                $adminRole->givePermissionTo($permission);
            }
        });
    }
}
