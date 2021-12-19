<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpSeries;
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
            DoublePumpWorkScheme::create(['id' => 1, 'name' => ['ru' => 'Рабочий-резервный', 'en' => 'Main standby']]);
            DoublePumpWorkScheme::create(['id' => 2, 'name' => ['ru' => 'Рабочий-пиковый', 'en' => 'Main peak']]);
            DB::table($database . '.model_has_roles')->update([
                'model_type' => PMUser::class
            ]);
            DB::table($database . '.model_has_permissions')->update([
                'model_type' => PMUser::class
            ]);
            DB::table('pumps')->update(['pumpable_type' => Pump::$SINGLE_PUMP]);
            $seriesIds = PumpSeries::pluck('id')->all();
            foreach ($seriesIds as $seriesId) {
                $pumps = Pump::whereSeriesId($seriesId);
                $maxmax = $pumps->max('fluid_temp_max');
                $minmax = $pumps->min('fluid_temp_max');
                $maxmin = $pumps->max('fluid_temp_min');
                $minmin = $pumps->min('fluid_temp_min');
                PumpSeries::find($seriesId)->update([
                    'temps_min' => ($minmin !== null && $maxmin !== null) ? implode(",", [$minmin, $maxmin]) : null,
                    'temps_max' => ($minmax !== null && $maxmax !== null) ? implode(',', [$minmax, $maxmax]) : null
                ]);
            }
        });
        SelectionType::whereId(1)->update(['pumpable_type' => Pump::$SINGLE_PUMP]);
        SelectionType::whereId(2)->update(['pumpable_type' => Pump::$DOUBLE_PUMP]);
        SelectionType::whereId(3)->update(['pumpable_type' => Pump::$STATION_WATER]);
        SelectionType::whereId(4)->update(['pumpable_type' => Pump::$STATION_FIRE]);
    }
}
