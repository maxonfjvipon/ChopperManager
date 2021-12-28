<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpsAndCoefficients;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\PMUser;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
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
            $peak_performances = DB::table($tenant->database . '.pumps')
                ->where('pumpable_type', Pump::$DOUBLE_PUMP)
                ->pluck('dp_peak_performance', 'id')
                ->all();
            $standby_performances = DB::table($tenant->database . '.pumps')
                ->where('pumpable_type', Pump::$DOUBLE_PUMP)
                ->pluck('dp_standby_performance', 'id')
                ->all();
//            dd($peak_performances, $standby_performances);
            $pumps = Pump::where('pumpable_type', Pump::$DOUBLE_PUMP)->get();
            foreach ($pumps as $pump) {
                $pump->update([
                    'dp_standby_performance' => $peak_performances[$pump->id],
                    'dp_peak_performance' => $standby_performances[$pump->id]
                ]);
            }
        });
    }
}
