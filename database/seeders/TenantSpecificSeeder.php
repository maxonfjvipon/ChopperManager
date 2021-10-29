<?php


namespace Database\Seeders;


use App\Models\ConnectionType;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\MainsConnection;
use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpCategory;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpSeriesAndApplication;
use App\Models\Pumps\PumpSeriesAndType;
use App\Models\Pumps\PumpType;
use App\Models\Selections\SelectionRange;
use App\Models\Users\Business;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TenantSpecificSeeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();



    }

}
