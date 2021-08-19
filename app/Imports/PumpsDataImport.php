<?php

namespace App\Imports;

use App\Models\pumps\Pump;
use App\Models\pumps\PumpAndApplication;
use App\Models\pumps\PumpAndType;
use App\Models\pumps\PumpApplication;
use App\Models\pumps\PumpProducer;
use App\Models\pumps\PumpSeries;
use App\Models\pumps\PumpSeriesAndRegulation;
use App\Models\pumps\PumpSeriesAndType;
use App\Models\pumps\PumpSeriesTemperatures;
use App\Models\pumps\PumpType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class PumpsDataImport implements ToCollection
{
    use Importable;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $rows->shift();

        $seriesAndTemperatures = [];
        $seriesAndTypes = [];
        $seriesAndRegulations = [];

        foreach ($rows as $row) {
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
                PumpAndApplication::firstOrCreate(
                    [
                        'pump_id' => $pump->id,
                        'application_id' => PumpApplication::whereName($application)->first()->id
                    ],
                    [
                        'pump_id' => $pump->id,
                        'application_id' => PumpApplication::whereName($application)->first()->id
                    ]);
            }

            foreach ($pumpTypes as $pumpType) {
                $filterTypeId = PumpType::whereName($pumpType)->first()->id;
                if (!in_array($filterTypeId, $seriesAndTypes[$series->id])) {
                    $seriesAndTypes[$series->id][] = $filterTypeId;
                }

                PumpAndType::firstOrCreate(
                    [
                        'pump_id' => $pump->id,
                        'type_id' => $filterTypeId
                    ],
                    [
                        'pump_id' => $pump->id,
                        'type_id' => $filterTypeId
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

        return "success";
    }
}
