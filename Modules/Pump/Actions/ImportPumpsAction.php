<?php

namespace Modules\Pump\Actions;

use App\Rules\ExistsAsKeyInArray;
use App\Rules\ExistsInArray;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpFile;
use Modules\Pump\Entities\PumpSeries;
use Modules\Selection\Support\PolynomialRegression;
use Modules\Selection\Support\PPumpPerformance;
use Modules\Selection\Support\PumpPerformance;
use Modules\Selection\Support\Regression;

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
            '17' => ['sometimes', 'nullable',], // description
            '18' => ['sometimes', 'nullable', 'string'], // pump image
            '19' => ['sometimes', 'nullable', 'string'], // pump sizes image
            '20' => ['sometimes', 'nullable', 'string'], // pump electric diagram image
            '21' => ['sometimes', 'nullable', 'string'], // pump cross sectional drawing image
            '22' => ['sometimes', 'nullable', 'string'], // pump files
        ], [
            // TODO: add attributes and translations for images and files
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
            '17' => __('validation.attributes.import.pumps.description'),
            '18' => __('validation.attributes.import.pumps.pump_image'),
            '19' => __('validation.attributes.import.pumps.pump_sizes_image'),
            '20' => __('validation.attributes.import.pumps.pump_electric_diagram_image'),
            '21' => __('validation.attributes.import.pumps.pump_cross_sectional_drawing_image'),
            '22' => __('validation.attributes.import.pumps.pump_files'),
        ], [], $files, 'pumps.index', __('flash.pumps.imported'));
    }

    protected function errorBagEntity($entity, $message): array
    {
        return [
            'head' => [
                'key' => __('validation.attributes.import.pumps.article_num_main'),
                'value' => $entity[0] !== "" ? $entity[0] : 'Unknown',
            ],
            'file' => '', // TODO
            'message' => $message[0],
        ];
    }

    protected function importEntity($entity): array
    {
        return [
            'pump' => [
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
                'description' => $entity[17] ?? null,
                'image' => array_key_exists(18, $entity) ? trim($entity[18]) : null,
                'sizes_image' => array_key_exists(19, $entity) ? trim($entity[19]) : null,
                'electric_diagram_image' => array_key_exists(20, $entity) ? trim($entity[20]) : null,
                'cross_sectional_drawing_image' => array_key_exists(21, $entity) ? trim($entity[21]) : null,
            ],
            'files' => array_key_exists(22, $entity) ? $this->idsArrayFromString($entity[22]) : []
        ];
    }

    protected function import($sheet)
    {
        $database = $this->getTenantModel()::current()->database;
        foreach (array_chunk($sheet, 100) as $chunkedSheet) {
            DB::table($database . '.pumps')->upsert(array_map(fn($sheetInfo) => $sheetInfo['pump'],
                $chunkedSheet), ['article_num_main']);
        }
        $seriesId = $sheet[0]['pump']['series_id'];
        $pumpsBySeries = Pump::whereSeriesId($seriesId);
        PumpSeries::whereId($seriesId)->update([
            'temp_min' => $pumpsBySeries->min('fluid_temp_min'),
            'temp_max' => $pumpsBySeries->max('fluid_temp_max'),
        ]);

        $upsertedPumps = $pumpsBySeries->get(['id', 'article_num_main']); // pump that were inserted just now (from current sheet)
        PumpFile::whereIn('pump_id', $upsertedPumps->pluck('id')->all())->delete();

        foreach (array_chunk($sheet, 100) as $chunkedSheet) {
            $pumpFiles = [];
            foreach ($chunkedSheet as $sheetRow) {
                $pumpId = $upsertedPumps->firstWhere('article_num_main', $sheetRow['pump']['article_num_main'])->id;
                foreach ($sheetRow['files'] as $fileName) {
                    if (!empty($fileName))
                        $pumpFiles[] = [
                            'pump_id' => $pumpId,
                            'file_name' => trim($fileName),
                        ];
                }
            }
            DB::table($database . '.pump_files')->insert($pumpFiles);
        }
        if ($this->createCoefs) {
            $pumpsBySeries->with('coefficients')->select('id', 'performance')->chunk(100, function ($pumps) use ($database) {
                DB::table($database . '.pumps_and_coefficients')
                    ->whereIn('pump_id', array_map(fn($pump) => $pump['id'], $pumps->toArray()))
                    ->delete();
                $pumpsAndCoefficients = [];
                foreach ($pumps as $pump) {
                    if ($pump->coefficients->isEmpty()) {
//                        $pumpPerformance = new PumpPerformance($pump->performance);
                        $pumpPerformance = new PPumpPerformance($pump);
                        for ($pos = 1; $pos < 10; ++$pos) {
                            $coefficients = PolynomialRegression::fromData($pumpPerformance->asArrayData($pos))->coefficients();
//                            $coefficients = Regression::withData($pumpPerformance->asLineData($pos))->polynomial()->coefficients();
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
                    DB::table($database . '.pumps_and_coefficients')->insert($pumpsAndCoefficients);
                }
            });
        }
    }
}
