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
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportPumpsAction
{
    public function execute($files): RedirectResponse
    {
        $createCoefs = true;
        ini_set('max_execution_time', 180);
        $db = [
            'brands' => PumpBrand::pluck('id', 'name')->all(),
            'series' => PumpSeries::pluck('id', 'name')->all(),
//            'currencies' => Currency::pluck('id', 'code')->all(),
            'connectionTypes' => ConnectionType::pluck('id')->all(),
            'dns' => DN::pluck('id', 'value')->all(),
//            'categories' => PumpCategory::pluck('id')->all(),
//            'types' => PumpType::pluck('id')->all(),
            'mainsConnections' => MainsConnection::pluck('id')->all(),
            'powerAdjustments' => ElPowerAdjustment::pluck('id')->all(),
//            'applications' => PumpApplication::pluck('id')->all(),
        ];
        $errorBar = [];

//      NEW FORMAT
        $rules = [
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
        ];

//        OLD FORMAT
//        $rules = [
//            '0' => ['required'], // main part num
//            '1' => ['nullable'], // backup part num
//            '2' => ['nullable'], // archive part num
//            '3' => ['required', new ExistsAsKeyInArray($db['brands'])], // brand,
//            '4' => ['required', new ExistsAsKeyInArray($db['series'])], // series
//            '5' => ['required'], // name
//            '6' => ['required'], // price
//            '7' => ['required', new ExistsAsKeyInArray($db['currencies'])], // currency
//            '8' => ['required'], // weight
//            '9' => ['required'], // rated power,
//            '10' => ['required'], // rated current
//            '11' => ['required', new ExistsInArray($db['connectionTypes'])], // connection type
//            '12' => ['required', new ExistsAsKeyInArray($db['dns'])], // dn
//            '13' => ['required', new ExistsAsKeyInArray($db['dns'])], // dn
//            '14' => ['required'], // min temp
//            '15' => ['required'], // max temp
//            '16' => ['required'], // ptp length
//            '17' => ['required', 'regex:/^\s*\d+((,|.)\d+)?(\s{1}\d+((,|.)\d+)?){9,19}\s*$/'], // performance
//            '18' => ['required', new ExistsInArray($db['categories'])], // category
//            '19' => ['required', new ExistsInArray($db['powerAdjustments'])], // power adjustment
//            '20' => ['required', new ExistsInArray($db['mainsConnections'])], // mains connection
//            '21' => ['required', 'regex:/^\s*(\d+){1}\s*(,\s*\d+\s*)*$/', new ExistsInArrayComplex($db['applications'], ",")], // applications
//            '22' => ['required', 'regex:/^\s*(\d+){1}\s*(,\s*\d+\s*)*$/', new ExistsInArrayComplex($db['types'], ",")] // type
//        ];

//        OLD FORMAT
//        $attributes = [
//            '0' => __('validation.attributes.import.pumps.article_num_main'),
//            '1' => __('validation.attributes.import.pumps.article_num_reserve'),
//            '2' => __('validation.attributes.import.pumps.article_num_archive'),
//            '3' => __('validation.attributes.import.pumps.brand'),
//            '4' => __('validation.attributes.import.pumps.series'),
//            '5' => __('validation.attributes.import.pumps.name'),
//            '6' => __('validation.attributes.import.pumps.price'),
//            '7' => __('validation.attributes.import.pumps.currency'),
//            '8' => __('validation.attributes.import.pumps.weight'),
//            '9' => __('validation.attributes.import.pumps.rated_power'),
//            '10' => __('validation.attributes.import.pumps.rated_current'),
//            '11' => __('validation.attributes.import.pumps.connection_type'),
//            '12' => __('validation.attributes.import.pumps.dn_suction'),
//            '13' => __('validation.attributes.import.pumps.dn_pressure'),
//            '14' => __('validation.attributes.import.pumps.fluid_temp_min'),
//            '15' => __('validation.attributes.import.pumps.fluid_temp_max'),
//            '16' => __('validation.attributes.import.pumps.ptp_length'),
//            '17' => __('validation.attributes.import.pumps.performance'),
//            '18' => __('validation.attributes.import.pumps.category'),
//            '19' => __('validation.attributes.import.pumps.power_adjustment'),
//            '20' => __('validation.attributes.import.pumps.mains_connection'),
//            '21' => __('validation.attributes.import.pumps.applications'),
//            '22' => __('validation.attributes.import.pumps.types'),
//        ];

        $attributes = [
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
        ];

        $messages = [];
        try {
            $sheets = [];
            foreach ($files as $file) {
                $sheets = (new FastExcel())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->importSheets($file, function ($pump) use ($db, &$errorBar, $rules, $attributes, $messages) {
                        try {
                            validator()->make($pump, $rules, $messages, $attributes)->validate();
                        } catch (ValidationException $exception) {
                            array_map(function ($message) use ($pump, &$errorBar) {
                                $errorBar[] = [
                                    'file' => '', // TODO
                                    'article_num' => $pump[0] !== "" ? $pump[0] : 'Unknown',
                                    'message' => $message[0],
                                ];
                            }, $exception->validator->errors()->messages());
                        }
                        if (empty($errorBar)) {
                            return [
                                'pump' => [
                                    'article_num_main' => trim($pump[0]),
                                    'article_num_reserve' => trim($pump[1]) ?? null,
                                    'article_num_archive' => trim($pump[2]) ?? null,
                                    'series_id' => $db['series'][$pump[4]],
                                    'name' => trim($pump[5]),
//                                'price' => $pump[6],
//                                'currency_id' => $db['currencies'][$pump[7]],
                                    'weight' => $pump[6],
                                    'rated_power' => $pump[7],
                                    'rated_current' => $pump[8],
                                    'connection_type_id' => $pump[9],
                                    'dn_suction_id' => $db['dns'][$pump[10]],
                                    'dn_pressure_id' => $db['dns'][$pump[11]],
                                    'fluid_temp_min' => $pump[12],
                                    'fluid_temp_max' => $pump[13],
                                    'ptp_length' => $pump[14],
                                    'connection_id' => trim($pump[15]),
                                    'performance' => trim($pump[16]),
//                                'category_id' => $pump[18],
                                ],
                                // TODO: get rid of this
//                            'regulation_adjustment_id' => $pump[19],
//                            'applications' => $pump[21],
//                            'types' => $pump[22]
                            ];
                        }
                    });
            }

            if (!empty($errorBar)) {
                return Redirect::back()->with('errorBag', array_splice($errorBar, 0, 50));
            }
            foreach ($sheets as $sheet) {
                foreach (array_chunk($sheet, 100) as $chunkedSheet) {
                    DB::table('pumps')->upsert(array_map(fn($sheetInfo) => $sheetInfo['pump'], $chunkedSheet), ['article_num_main']);
                }
                $seriesId = $sheet[0]['pump']['series_id'];
                $pumpsBySeries = Pump::whereSeriesId($seriesId);
                PumpSeries::whereId($seriesId)->update([
//                    'regulation_adjustment_id' => $sheet[0]['regulation_adjustment_id'],
                    'temp_min' => $pumpsBySeries->min('fluid_temp_min'),
                    'temp_max' => $pumpsBySeries->max('fluid_temp_max'),
                ]);
                if ($createCoefs) {
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
//                DB::table('pump_series_and_applications')->whereSeriesId($seriesId)->delete();
//                DB::table('pump_series_and_applications')->insert(
//                    array_map(fn($applicationId) => [
//                        'series_id' => $seriesId,
//                        'application_id' => trim($applicationId)
//                    ], explode(",", $sheet[0]['applications'])),
//                );
//                DB::table('pump_series_and_types')->whereSeriesId($seriesId)->delete();
//                DB::table('pump_series_and_types')->insert(
//                    array_map(fn($typeId) => [
//                        'series_id' => $seriesId,
//                        'type_id' => trim($typeId)
//                    ], explode(",", $sheet[0]['types'])),
//                );
            }
        } catch (IOException | UnsupportedTypeException | ReaderNotOpenedException | Exception $exception) {
            Log::error($exception->getMessage());
            return Redirect::route('pumps.index')
                ->withErrors(__('validation.import.exception', ['attribute' => __('validation.attributes.pumps')]));
        }
        return Redirect::route('pumps.index')->with('success', __('flash.pumps.imported'));
    }
}
