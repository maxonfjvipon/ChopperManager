<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\Pump;
use Modules\PumpManager\Entities\PMUser;
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
            $database = $tenant->database;
            DoublePumpWorkScheme::create(['name' => ['ru' => 'Рабочий-пиковый', 'en' => 'Main peak']]);
            DoublePumpWorkScheme::create(['name' => ['ru' => 'Рабочий-резервный', 'en' => 'Main standby']]);
            DB::table($database . '.model_has_roles')->update([
                'model_type' => PMUser::class
            ]);
            DB::table($database . '.model_has_permissions')->update([
                'model_type' => PMUser::class
            ]);
            DB::table('pumps')->update(['pumpable_type' => Pump::$SINGLE_PUMP]);
        });
        SelectionType::whereId(1)->update(['pumpable_type' => Pump::$SINGLE_PUMP]);
        SelectionType::whereId(2)->update(['pumpable_type' => Pump::$DOUBLE_PUMP]);
        SelectionType::whereId(3)->update(['pumpable_type' => Pump::$STATION_WATER]);
        SelectionType::whereId(4)->update(['pumpable_type' => Pump::$STATION_FIRE]);
    }
}
