<?php

namespace Modules\Pump\Database\Seeders;

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
use Database\Seeders\AdminSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PumpDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** * Pump applications */
        PumpApplication::create(['name' => ['ru' => "Водозабор", 'en' => 'Water intake']]);
        PumpApplication::create(['name' => ['ru' => "Водоснабжение", 'en' => 'Water supply']]);
        PumpApplication::create(['name' => ['ru' => "Горячее водоснабжение", 'en' => 'Hot water supply']]);
        PumpApplication::create(['name' => ['ru' => "Дождевая вода", 'en' => 'Rain water']]);
        PumpApplication::create(['name' => ['ru' => "Кондиционирование/охлаждение", 'en' => 'Air-conditioning/cooling']]);
        PumpApplication::create(['name' => ['ru' => "Отопление", 'en' => 'Heating']]);
        PumpApplication::create(['name' => ['ru' => "Повышение давления", 'en' => 'Increasing the pressure']]);

        /** * Pump regulations */
        ElPowerAdjustment::create(['name' => ['ru' => "Да", 'en' => 'Yes']]);
        ElPowerAdjustment::create(['name' => ['ru' => "Нет", 'en' => 'No']]);

        /** * Pump categories */
        PumpCategory::create(['name' => ['ru' => "Одинарный", 'en' => 'Single']]);
        PumpCategory::create(['name' => ['ru' => "Сдвоенный", 'en' => 'Double']]);

        /** * Mains phases */
        MainsConnection::create(['id' => 1, 'phase' => 1, 'voltage' => 220]);
        MainsConnection::create(['id' => 3, 'phase' => 3, 'voltage' => 380]);

        /** * DNs */
        DN::create(['value' => 15]);
        DN::create(['value' => 20]);
        DN::create(['value' => 25]);
        DN::create(['value' => 32]);
        DN::create(['value' => 40]);
        DN::create(['value' => 50]);
        DN::create(['value' => 65]);
        DN::create(['value' => 80]);
        DN::create(['value' => 100]);
        DN::create(['value' => 125]);
        DN::create(['value' => 150]);
        DN::create(['value' => 200]);
        DN::create(['value' => 250]);
        DN::create(['value' => 300]);

        /** * Pump types */
        PumpType::create(['name' => ['ru' => 'Ин-лайн', 'en' => 'In-line']]);
        PumpType::create(['name' => ['ru' => 'Консольно-моноблочный', 'en' => 'Console monoblock']]);
        PumpType::create(['name' => ['ru' => 'Консольный', 'en' => 'Console']]);
        PumpType::create(['name' => ['ru' => 'Мокрый ротор', 'en' => 'Wet rotor']]);
        PumpType::create(['name' => ['ru' => 'Сухой ротор', 'en' => 'Dry rotor']]);
        PumpType::create(['name' => ['ru' => 'Многоступенчатый', 'en' => 'Multi-stage']]);
        PumpType::create(['name' => ['ru' => 'Центробежный', 'en' => 'Centrifugal']]);
        PumpType::create(['name' => ['ru' => 'Самовсасывающий', 'en' => 'Self-priming']]);
        PumpType::create(['name' => ['ru' => 'Погружной', 'en' => 'Submersible']]);
        PumpType::create(['name' => ['ru' => 'Дренажный', 'en' => 'Drainage']]);
        PumpType::create(['name' => ['ru' => 'Одноступенчатый', 'en' => 'Single-stage']]);

        /** * Connection types */
        ConnectionType::create(['name' => ['ru' => 'Резьбовой', 'en' => 'Threaded']]);
        ConnectionType::create(['name' => ['ru' => 'Фланцевый', 'en' => 'Flanged']]);

        /** * Pump Brands */
        PumpBrand::create(['name' => 'Wilo']);

        /** * Pump Series */
        PumpSeries::create(['name' => 'MHI', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 1, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 1, 'type_id' => 6]);
        PumpSeriesAndType::create(['series_id' => 1, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 1, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 1, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 1, 'application_id' => 7]);
        PumpSeries::create(['name' => 'BL', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 2, 'type_id' => 2]);
        PumpSeriesAndType::create(['series_id' => 2, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 2, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 5]);
        PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 6]);
        PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 7]);
        PumpSeries::create(['name' => 'TOP-S', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 3, 'type_id' => 4]);
        PumpSeriesAndApplication::create(['series_id' => 3, 'application_id' => 5]);
        PumpSeriesAndApplication::create(['series_id' => 3, 'application_id' => 6]);
        PumpSeries::create(['name' => 'TOP-Z', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 4, 'type_id' => 4]);
        PumpSeriesAndApplication::create(['series_id' => 4, 'application_id' => 3]);
        PumpSeries::create(['name' => 'IL-E', 'brand_id' => 1, 'power_adjustment_id' => 1, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 5, 'type_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 5, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 5, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 5]);
        PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 6]);
        PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 7]);
        PumpSeries::create(['name' => 'IPL', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 6, 'type_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 6, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 6, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 5]);
        PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 6]);
        PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 7]);
        PumpSeries::create(['name' => 'IL', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 7, 'type_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 7, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 7, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 5]);
        PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 6]);
        PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 7]);
        PumpSeries::create(['name' => 'MVI', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 8, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 8, 'type_id' => 6]);
        PumpSeriesAndType::create(['series_id' => 8, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 8, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 8, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 8, 'application_id' => 7]);
        PumpSeries::create(['name' => 'Helix First', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 9, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 9, 'type_id' => 6]);
        PumpSeriesAndType::create(['series_id' => 9, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 9, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 9, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 9, 'application_id' => 7]);
        PumpSeries::create(['name' => 'Helix V', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
        PumpSeriesAndType::create(['series_id' => 10, 'type_id' => 5]);
        PumpSeriesAndType::create(['series_id' => 10, 'type_id' => 6]);
        PumpSeriesAndType::create(['series_id' => 10, 'type_id' => 7]);
        PumpSeriesAndApplication::create(['series_id' => 10, 'application_id' => 2]);
        PumpSeriesAndApplication::create(['series_id' => 10, 'application_id' => 3]);
        PumpSeriesAndApplication::create(['series_id' => 10, 'application_id' => 7]);
        PumpSeries::create(['name' => 'Stres', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
    }
}

