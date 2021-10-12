<?php

namespace App\Imports\Sheets;

use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpSeriesAndApplication;
use App\Models\Pumps\PumpSeriesAndType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;

class SpecifiedSeriesSheet implements ToCollection, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        $rows->shift();
        $series = PumpSeries::whereName($rows[0][4])->whereBrandId(PumpBrand::firstWhere('name', $rows[0][3])->id)->first();

        $series->update([
            'regulation_adjustment_id' => $rows[0][19],
            'temp_min' => $rows->min(fn($row) => $row[14]),
            'temp_max' => $rows->max(fn($row) => $row[15])
        ]);

        // pump series and applications
        PumpSeriesAndApplication::whereSeriesId($series->id)->delete();
        DB::table('pump_series_and_applications')->insert(
            array_map(
                fn($applicationId) => ['series_id' => $series->id, 'application_id' => $applicationId],
                explode(",", $rows[0][21])
            )
        );

        // pump series and types
        PumpSeriesAndType::whereSeriesId($series->id)->delete();
        DB::table('pump_series_and_types')->insert(
            array_map(
                fn($applicationId) => ['series_id' => $series->id, 'type_id' => $applicationId],
                explode(",", $rows[0][22])
            )
        );
    }
}
