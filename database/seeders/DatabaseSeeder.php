<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Entities\ProjectDeliveryStatus;
use Modules\Core\Entities\ProjectStatus;
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

    }
}
