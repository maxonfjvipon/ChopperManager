<?php

namespace App\Imports;

use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpsAndCoefficients;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpSeriesAndApplication;
use App\Models\Pumps\PumpSeriesAndType;
use App\Models\Pumps\PumpSeriesTemperatures;
use App\Models\Pumps\PumpType;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;

class PumpsDataImport implements ToCollection, SkipsEmptyRows
{
    use Importable;

    /**
     * @param Collection $rows
     * @return string
     */
    public function collection(Collection $rows): string
    {
        $rows->shift();

        $seriesStaticInfo = [];
        $seriesAndTemperatures = [];
        $seriesAndTypes = [];
//        $seriesAndPowerAdjustments = [];
        $seriesAndApplications = [];

        foreach ($rows as $row) {
            $pumpPerformance = new PumpPerformance(trim($row[17]));
            $pump = Pump::where('article_num_main', trim($row[0]))->first();

            for ($position = 1; $position < 10; ++$position) {
                $coefficients = Regression::withData($pumpPerformance->lineData($position))->polynomial()->coefficients();
                PumpsAndCoefficients::create([
                    'pump_id' => $pump->id,
                    'position' => $position,
                    'k' => $coefficients[0],
                    'b' => $coefficients[1],
                    'c' => $coefficients[2]
                ]);
            }

            $series = PumpSeries::whereName($row[4])
                ->whereBrandId(PumpBrand::firstWhere('name', $row[3])->id)
                ->first();

            if (!array_key_exists($series->id, $seriesStaticInfo)) {
                $seriesStaticInfo[$series->id] = [
                    'regulation_adjustment_id' => $row[19],
                    'temp_min' => 1000,
                    'temp_max' => -100,
                ];
            }

//            if (!array_key_exists($series->id, $seriesAndTemperatures)) {
//                $seriesAndTemperatures[$series->id] = [
//                    'temp_min' => 1000,
//                    'temp_max' => -100,
//                ];
//            }
            if (!array_key_exists($series->id, $seriesAndTypes)) {
                $seriesAndTypes[$series->id] = [];
            }
            if (!array_key_exists($series->id, $seriesAndApplications)) {
                $seriesAndApplications[$series->id] = [];
            }
//            if (!array_key_exists($series->id, $seriesAndPowerAdjustments)) {
//                $seriesAndPowerAdjustments[$series->id] = [];
//            }

            // temp min
            if ($pump->fluid_temp_min < $seriesStaticInfo[$series->id]['temp_min']) {
                $seriesStaticInfo[$series->id]['temp_min'] = $pump->fluid_temp_min;
            }

            // temp max
            if ($pump->fluid_temp_max > $seriesStaticInfo[$series->id]['temp_max']) {
                $seriesStaticInfo[$series->id]['temp_max'] = $pump->fluid_temp_max;
            }

//            // regulations
//            if (!in_array($pump->regulation_id, $seriesAndPowerAdjustments[$series->id])) {
//                $seriesAndPowerAdjustments[$series->id][] = $pump->regulation_id;
//            }

            $applications = explode(",", $row[21]);
            $pumpTypes = explode(",", $row[22]);

            foreach ($applications as $applicationId) {
                if (!in_array($applicationId, $seriesAndApplications[$series->id])) {
                    $seriesAndApplications[$series->id][] = $applicationId;
                }
            }

            foreach ($pumpTypes as $pumpTypeId) {
                if (!in_array($pumpTypeId, $seriesAndTypes[$series->id])) {
                    $seriesAndTypes[$series->id][] = $pumpTypeId;
                }
            }
        }

        foreach ($seriesStaticInfo as $seriesId => $info) {
            PumpSeries::find($seriesId)->update([
                'regulation_adjustment_id' => $info['regulation_adjustment_id'],
                'temp_min' => $info['temp_min'],
                'temp_max' => $info['temp_max']
            ]);
        }

//        // series and temperatures
//        foreach ($seriesAndTemperatures as $seriesId => $temp) {
//            PumpSeries::find($seriesId)->update([
//                'temp_min' => $temp['temp_min'],
//                'temp_max' => $temp['temp_max']
//            ]);
//        }

        // series and types
        foreach ($seriesAndTypes as $seriesId => $typeIds) {
            foreach ($typeIds as $typeId) {
                PumpSeriesAndType::firstOrCreate([
                    'series_id' => $seriesId,
                    'type_id' => $typeId
                ], [
                    'series_id' => $seriesId,
                    'type_id' => $typeId
                ]);
            }
        }

//        // series and regulations
//        foreach ($seriesAndPowerAdjustments as $seriesId => $regulationIds) {
//            foreach ($regulationIds as $regulationId) {
//                PumpSeriesAndPowerAdjustment::firstOrCreate([
//                    'series_id' => $seriesId,
//                    'regulation_id' => $regulationId
//                ], [
//                    'series_id' => $seriesId,
//                    'regulation_id' => $regulationId
//                ]);
//            }
//        }

        // series and applications
        foreach ($seriesAndApplications as $seriesId => $applicationIds) {
            foreach ($applicationIds as $applicationId) {
                PumpSeriesAndApplication::firstOrCreate([
                    'series_id' => $seriesId,
                    'application_id' => $applicationId
                ], [
                    'series_id' => $seriesId,
                    'application_id' => $applicationId
                ]);
            }
        }

        return "success";
    }
}
