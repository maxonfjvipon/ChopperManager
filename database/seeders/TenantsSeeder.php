<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Entities\ProjectDeliveryStatus;
use Modules\Core\Entities\ProjectStatus;
use Modules\User\Entities\Permission;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class TenantsSeeder extends Seeder
{
    use UsesTenantModel;

    public function run()
    {
        Tenant::all()->eachCurrent(function (Tenant $tenant) {
            ProjectStatus::updateOrCreate(['id' => 1], ['name' => ['ru' => "Новый", 'en' => 'New']]);
            ProjectStatus::updateOrCreate(['id' => 2], ['name' => ['ru' => "В работе", 'en' => 'In progress']]);
            ProjectStatus::updateOrCreate(['id' => 3], ['name' => ['ru' => "Архивный", 'en' => 'Archived']]);
            ProjectStatus::updateOrCreate(['id' => 4], ['name' => ['ru' => "Удаленный", 'en' => 'Deleted']]);

            ProjectDeliveryStatus::updateOrCreate(['id' => 1], [
                'name' => ['ru' => 'Не поставлен', 'en' => 'Not delivered']]);
            ProjectDeliveryStatus::updateOrCreate(['id' => 2], [
                'name' => ['ru' => 'Поставлен', 'en' => 'Delivered']]);

            DB::table($tenant->database . '.projects')
                ->whereNull('status_id')
                ->whereNull('delivery_status_id')
                ->update([
                    'status_id' => 1,
                    'delivery_status_id' => 1
                ]);

            DB::table($tenant->database . '.projects')
                ->whereNotNull('deleted_at')
                ->whereNotIn('status_id', [3, 4])
                ->update(['status_id' => 4]);

            $pr1 = Permission::updateOrCreate(['name' => 'project_statistics', 'guard_name' => $tenant->guard],
                ['name' => 'project_statistics', 'guard_name' => $tenant->guard]);
            $pr2 = Permission::updateOrCreate(['name' => 'user_statistics', 'guard_name' => $tenant->guard],
                ['name' => 'user_statistics', 'guard_name' => $tenant->guard]);

            DB::table($tenant->database . '.role_has_permissions')
                ->insertOrIgnore([
                    ['permission_id' => $pr1->id, 'role_id' => 1],
                    ['permission_id' => $pr1->id, 'role_id' => 2],
                    ['permission_id' => $pr2->id, 'role_id' => 1],
                    ['permission_id' => $pr2->id, 'role_id' => 2],
                    ['permission_id' => 11, 'role_id' => 2], // user edit
                    ['permission_id' => 50, 'role_id' => 2], // price list import
                    ['permission_id' => 51, 'role_id' => 2], // pump import
                    ['permission_id' => 52, 'role_id' => 2], // pump import media
                    ['permission_id' => 54, 'role_id' => 2], // series import
                    ['permission_id' => 55, 'role_id' => 2], // series import media
                ]);

            DB::table($tenant->database . '.users')->whereNull('last_login_at')->update([
                'last_login_at' => now()
            ]);
        });
    }
}
