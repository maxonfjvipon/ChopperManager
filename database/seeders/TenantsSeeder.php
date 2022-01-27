<?php

namespace Database\Seeders;

use http\Client\Curl\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Entities\ProjectDeliveryStatus;
use Modules\Core\Entities\ProjectStatus;
use Modules\PumpManager\Entities\PMUser;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Userable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class TenantsSeeder extends Seeder
{
    use UsesTenantModel;

    public function run()
    {
        Tenant::all()->eachCurrent(function (Tenant $tenant) {
            ProjectStatus::create(['name' => ['ru' => "Новый", 'en' => 'New']]);
            ProjectStatus::create(['name' => ['ru' => "В работе", 'en' => 'In progress']]);
            ProjectStatus::create(['name' => ['ru' => "Архивный", 'en' => 'Archived']]);
            ProjectStatus::create(['name' => ['ru' => "Удаленный", 'en' => 'Deleted']]);

            ProjectDeliveryStatus::create(['name' => ['ru' => 'Не поставлен', 'en' => 'Not delivered']]);
            ProjectDeliveryStatus::create(['name' => ['ru' => 'Поставлен', 'en' => 'Delivered']]);

            DB::table($tenant->database . '.projects')->update([
                'status_id' => 1,
                'delivery_status_id' => 1
            ]);
            $pr1 = Permission::create(['name' => 'project_statistic', 'guard_name' => $tenant->guard]);
            $pr2 = Permission::create(['name' => 'user_statistic', 'guard_name' => $tenant->guard]);

            DB::table($tenant->database . '.role_has_permissions')
                ->insert([
                    ['permission_id' => $pr1->id, 'role_id' => 1],
                    ['permission_id' => $pr1->id, 'role_id' => 2],
                    ['permission_id' => $pr2->id, 'role_id' => 1],
                    ['permission_id' => $pr2->id, 'role_id' => 2],
                ]);
        });
    }
}
