<?php

namespace App\Actions;

use App\Models\ConnectionType;
use App\Models\DN;
use App\Models\MainsConnection;
use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpSeries;
use App\Rules\ExistsAsKeyInArray;
use App\Rules\ExistsInArray;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use Illuminate\Support\Facades\DB;

class ImportPumpsAction extends ImportAction
{
    private bool $createCoefs = true;

    public function __construct($files)
    {
        $db = [
            'brands' => PumpBrand::pluck('id', 'name')->all(),
            'series' => PumpSeries::pluck('id', 'name')->all(),
            'connectionTypes' => ConnectionType::pluck('id')->all(),
            'dns' => DN::pluck('id', 'value')->all(),
            'mainsConnections' => MainsConnection::pluck('id')->all(),
            'powerAdjustments' => ElPowerAdjustment::pluck('id')->all(),
        ];
        parent::__construct($db, [
            '0' => ['required'], // main part num
            '1' => ['nullable'], // backup part num
            '2' => ['nullable'], // archive part num
            '3' => ['required', new ExistsAsKeyInArray($db['brands'])], // brand,
            '4' => ['required', new ExistsAsKeyInArray($db['series'])], // series
            '5' => ['required'], // name
            '6' => ['required'], // weight
            '7' => ['required'], // rated power,
            '8' => ['required'], // rated current
            '9' => ['required', new ExistsInArray($db['connectionTypes'])], // connection type
            '10' => ['required', new ExistsAsKeyInArray($db['dns'])], // dn
            '11' => ['required', new ExistsAsKeyInArray($db['dns'])], // dn
            '12' => ['required'], // min temp
            '13' => ['required'], // max temp
            '14' => ['required'], // ptp length
            '15' => ['required', new ExistsInArray($db['mainsConnections'])], // mains connection
            '16' => ['required', 'regex:/^\s*\d+((,|.)\d+)?(\s{1}\d+((,|.)\d+)?){9,29}\s*$/'], // performance
        ], [
            '0' => __('validation.attributes.import.pumps.article_num_main'),
            '1' => __('validation.attributes.import.pumps.article_num_reserve'),
            '2' => __('validation.attributes.import.pumps.article_num_archive'),
            '3' => __('validation.attributes.import.pumps.brand'),
            '4' => __('validation.attributes.import.pumps.series'),
            '5' => __('validation.attributes.import.pumps.name'),
            '6' => __('validation.attributes.import.pumps.weight'),
            '7' => __('validation.attributes.import.pumps.rated_power'),
            '8' => __('validation.attributes.import.pumps.rated_current'),
            '9' => __('validation.attributes.import.pumps.connection_type'),
            '10' => __('validation.attributes.import.pumps.dn_suction'),
            '11' => __('validation.attributes.import.pumps.dn_pressure'),
            '12' => __('validation.attributes.import.pumps.fluid_temp_min'),
            '13' => __('validation.attributes.import.pumps.fluid_temp_max'),
            '14' => __('validation.attributes.import.pumps.ptp_length'),
            '15' => __('validation.attributes.import.pumps.mains_connection'),
            '16' => __('validation.attributes.import.pumps.performance'),
        ], [], $files, 'pumps.index', __('flash.pumps.imported'));
    }

    protected function errorBagEntity($entity, $message): array
    {
        return [
            'file' => '', // TODO
            'article_num' => $entity[0] !== "" ? $entity[0] : 'Unknown',
            'message' => $message[0],
        ];
    }

    protected function importEntity($entity): array
    {
        return [
            'article_num_main' => trim($entity[0]),
            'article_num_reserve' => trim($entity[1]) ?? null,
            'article_num_archive' => trim($entity[2]) ?? null,
            'series_id' => $this->db['series'][$entity[4]],
            'name' => trim($entity[5]),
            'weight' => $entity[6],
            'rated_power' => $entity[7],
            'rated_current' => $entity[8],
            'connection_type_id' => $entity[9],
            'dn_suction_id' => $this->db['dns'][$entity[10]],
            'dn_pressure_id' => $this->db['dns'][$entity[11]],
            'fluid_temp_min' => $entity[12],
            'fluid_temp_max' => $entity[13],
            'ptp_length' => $entity[14],
            'connection_id' => trim($entity[15]),
            'performance' => trim($entity[16]),
        ];
    }

    protected function import($sheet)
    {
        foreach (array_chunk($sheet, 100) as $chunkedSheet) {
            DB::table('pumps')->upsert(array_map(fn($sheetInfo) => $sheetInfo['pump'], $chunkedSheet), ['article_num_main']);
        }
        $seriesId = $sheet[0]['pump']['series_id'];
        $pumpsBySeries = Pump::whereSeriesId($seriesId);
        PumpSeries::whereId($seriesId)->update([
            'temp_min' => $pumpsBySeries->min('fluid_temp_min'),
            'temp_max' => $pumpsBySeries->max('fluid_temp_max'),
        ]);
        if ($this->createCoefs) {
            $pumpsBySeries->with('coefficients')->select('id', 'performance')->chunk(100, function ($pumps) {
                DB::table('pumps_and_coefficients')
                    ->whereIn('pump_id', array_map(fn($pump) => $pump['id'], $pumps->toArray()))
                    ->delete();
                $pumpsAndCoefficients = [];
                foreach ($pumps as $pump) {
                    if ($pump->coefficients->isEmpty()) {
                        $pumpPerformance = PumpPerformance::by($pump->performance);
                        for ($pos = 1; $pos < 10; ++$pos) {
                            $coefficients = Regression::withData($pumpPerformance->lineData($pos))->polynomial()->coefficients();
                            $pumpsAndCoefficients[] = [
                                'pump_id' => $pump->id,
                                'position' => $pos,
                                'k' => $coefficients[0],
                                'b' => $coefficients[1],
                                'c' => $coefficients[2]
                            ];
                        }
                    }
                }
                if (!empty($pumpsAndCoefficients)) {
                    DB::table('pumps_and_coefficients')->insert($pumpsAndCoefficients);
                }
            });
        }
    }
}
