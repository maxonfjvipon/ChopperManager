<?php


namespace App\Actions;


use App\Models\Currency;
use App\Models\Pumps\Pump;
use App\Models\Users\Country;
use App\Rules\ExistsAsKeyInArray;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;
use Exception;

class ImportPumpsPriceListsAction
{
    public function execute($files): RedirectResponse
    {
        ini_set('max_execution_time', 180);
        $db = [
            'pumps' => Pump::pluck('id', 'article_num_main')->all(),
            'countries' => Country::pluck('id', 'code')->all(),
            'currencies' => Currency::pluck('id', 'code')->all(),
        ];
        $errorBar = [];
        $rules = [
            '0' => ['required', new ExistsAsKeyInArray($db['pumps'])], // article num main
            '1' => ['required', new ExistsAsKeyInArray($db['countries'])], // countries
            '2' => ['required', new ExistsAsKeyInArray($db['currencies'])], // currencies
            '3' => ['required', 'numeric']
        ];
        $attributes = [
            '0' => __('validation.attributes.import.price_lists.article_num_main'),
            '1' => __('validation.attributes.import.price_lists.country'),
            '2' => __('validation.attributes.import.price_lists.currency'),
            '3' => __('validation.attributes.import.price_lists.price'),
        ];

        $messages = [];
        try {
            $sheets = [];
            foreach ($files as $file) {
                $sheets = (new FastExcel())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->importSheets($file, function ($price_list) use ($db, &$errorBar, $rules, $attributes, $messages) {
                        try {
                            validator()->make($price_list, $rules, $messages, $attributes)->validate();
                        } catch (ValidationException $exception) {
                            array_map(function ($message) use ($price_list, &$errorBar) {
                                $errorBar[] = [
                                    'file' => '', // TODO
                                    'article_num' => $price_list[0] !== "" ? $price_list[0] : 'Unknown',
                                    'message' => $message[0],
                                ];
                            }, $exception->validator->errors()->messages());
                        }
                        if (empty($errorBar)) {
                            return [
                                'pump_id' => $db['pumps'][trim($price_list[0])],
                                'country_id' => $db['countries'][trim($price_list[1])],
                                'currency_id' => $db['currencies'][trim($price_list[2])],
                                'price' => trim($price_list[3])
                            ];
                        }
                    });
            }

            if (!empty($errorBar)) {
                return Redirect::back()->with('errorBag', array_splice($errorBar, 0, 50));
            }
            foreach ($sheets as $sheet) {
                foreach (array_chunk($sheet, 100) as $chunkedSheet) {
                    DB::table('pumps_price_lists')->upsert($chunkedSheet, ['pump_id', 'country_id'], ['price', 'currency_id']);
                }
            }
        } catch (IOException | UnsupportedTypeException | ReaderNotOpenedException | Exception $exception) {
            Log::error($exception->getMessage());
            return Redirect::route('pumps.index')
                ->withErrors(__('validation.import.exception', ['attribute' => __('validation.attributes.price_lists')]));
        }
        return Redirect::route('pumps.index')->with('success', __('flash.price_lists.imported'));
    }
}
