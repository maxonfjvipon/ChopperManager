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
            $database = $tenant->database;
            DB::table($database . '.pumps_and_coefficients')->delete();
            $pumps = Pump::all();
            $pumpsAndCoefficients = [];
            foreach ($pumps as $pump) {
                if ($pump->pumpable_type === Pump::$SINGLE_PUMP) {
                    $pump->update([
                        'sp_performance' => str_replace(',', '.', $pump->sp_performance)
                    ]);
                } else {
                    $pump->update([
                        'dp_peak_performance' => str_replace(',', '.', $pump->dp_peak_performance),
                        'dp_standby_performance' => str_replace(',', '.', $pump->dp_standby_performance)
                    ]);
                }
                $count = $pump->coefficientsCount();
                $pumpPerformance = PPumpPerformance::construct($pump);
                for ($pos = 1; $pos <= $count; ++$pos) {
                    $pumpsAndCoefficients[] = $pumpPerformance->coefficientsToCreate($pos);
                }
            }
            DB::table($database . '.pumps_and_coefficients')->insert($pumpsAndCoefficients);
        });
    }
}
