<?php

namespace App\Imports;

use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpAndApplication;
use App\Models\Pumps\PumpAndType;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpsAndCoefficients;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpSeriesAndApplication;
use App\Models\Pumps\PumpSeriesAndRegulation;
use App\Models\Pumps\PumpSeriesAndType;
use App\Models\Pumps\PumpSeriesTemperatures;
use App\Models\Pumps\PumpType;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class PumpsDataImport implements ToCollection
{
    use Importable;

    /**
     * @param Collection $rows
     * @return string
     */
    public function collection(Collection $rows): string
    {
        $rows->shift();

        $seriesAndTemperatures = [];
        $seriesAndTypes = [];
        $seriesAndRegulations = [];
        $seriesAndApplications = [];

        foreach ($rows as $row) {
            $pumpPerformance = new PumpPerformance(trim($row[17]));
            for ($count = 1; $count < 10; ++$count) {
                $coefficients = Regression::withData($pumpPerformance->lineData($count))->polynomial()->coefficients();
                PumpsAndCoefficients::create([
                    'pump_id' => Pump::wherePartNumMain(trim($row[0]))->first()->id,
                    'count' => $count,
                    'k' => $coefficients[0],
                    'b' => $coefficients[1],
                    'c' => $coefficients[2]
                ]);
            }

            $series = PumpSeries::whereName($row[4])
                ->whereProducerId(PumpProducer::whereName($row[3])->first()->id)
                ->first();
//
            if (!array_key_exists($series->id, $seriesAndTemperatures)) {
                $seriesAndTemperatures[$series->id] = [
                    'temp_min' => 1000,
                    'temp_max' => -100,
                ];
            }
            if (!array_key_exists($series->id, $seriesAndTypes)) {
                $seriesAndTypes[$series->id] = [];
            }
            if (!array_key_exists($series->id, $seriesAndApplications)) {
                $seriesAndApplications[$series->id] = [];
            }
            if (!array_key_exists($series->id, $seriesAndRegulations)) {
                $seriesAndRegulations[$series->id] = [];
            }

            $pump = Pump::wherePartNumMain($row[0])->first();

            // temp min
            if ($pump->min_liquid_temp < $seriesAndTemperatures[$series->id]['temp_min']) {
                $seriesAndTemperatures[$series->id]['temp_min'] = $pump->min_liquid_temp;
            }

            // temp max
            if ($pump->max_liquid_temp > $seriesAndTemperatures[$series->id]['temp_max']) {
                $seriesAndTemperatures[$series->id]['temp_max'] = $pump->max_liquid_temp;
            }

            // regulations
            if (!in_array($pump->regulation_id, $seriesAndRegulations[$series->id])) {
                $seriesAndRegulations[$series->id][] = $pump->regulation_id;
            }

            $applications = explode(", ", $row[21]);
            $pumpTypes = explode(", ", $row[22]);

            foreach ($applications as $application) {
                $applicationId = PumpApplication::whereName($application)->first()->id;
                if (!in_array($applicationId, $seriesAndApplications[$series->id])) {
                    $seriesAndApplications[$series->id][] = $applicationId;
                }
                PumpAndApplication::firstOrCreate(
                    [
                        'pump_id' => $pump->id,
                        'application_id' => $applicationId
                    ],
                    [
                        'pump_id' => $pump->id,
                        'application_id' => $applicationId
                    ]);
            }

            foreach ($pumpTypes as $pumpType) {
                $pumpTypeId = PumpType::whereName($pumpType)->first()->id;
                if (!in_array($pumpTypeId, $seriesAndTypes[$series->id])) {
                    $seriesAndTypes[$series->id][] = $pumpTypeId;
                }
                PumpAndType::firstOrCreate(
                    [
                        'pump_id' => $pump->id,
                        'type_id' => $pumpTypeId
                    ],
                    [
                        'pump_id' => $pump->id,
                        'type_id' => $pumpTypeId
                    ]);
            }
        }

        // series and temperatures
        foreach ($seriesAndTemperatures as $seriesId => $temp) {
            PumpSeriesTemperatures::updateOrCreate(
                ['series_id' => $seriesId],
                [
                    'series_id' => $seriesId,
                    'temp_min' => $temp['temp_min'],
                    'temp_max' => $temp['temp_max'],
                ]);
        }

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

        // series and regulations
        foreach ($seriesAndRegulations as $seriesId => $regulationIds) {
            foreach ($regulationIds as $regulationId) {
                PumpSeriesAndRegulation::firstOrCreate([
                    'series_id' => $seriesId,
                    'regulation_id' => $regulationId
                ], [
                    'series_id' => $seriesId,
                    'regulation_id' => $regulationId
                ]);
            }
        }

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
