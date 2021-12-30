<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\User\Entities\Permission;
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
        Tenant::all()->eachCurrent(function (Tenant $tenant) {
            $permission = Permission::create([
                'guard_name' => $tenant->guard,
                'name' => 'project_clone'
            ]);
            $roles = Role::pluck('id')->all();
            DB::table($tenant->database . '.role_has_permissions')
                ->insert(array_map(fn($roleId) => [
                    'role_id' => $roleId, 'permission_id' => $permission->id
                ], $roles));
        });
    }
}
