<?php


namespace App\Actions;


use App\Models\Currency;
use App\Models\Pumps\Pump;
use App\Models\Users\Country;
use App\Rules\ExistsAsKeyInArray;
use Illuminate\Support\Facades\DB;

class ImportPumpsPriceListsAction extends ImportAction
{
    public function __construct($files)
    {
        $db = [
            'pumps' => Pump::pluck('id', 'article_num_main')->all(),
            'countries' => Country::pluck('id', 'code')->all(),
            'currencies' => Currency::pluck('id', 'code')->all(),
        ];
        parent::__construct($db, [
            '0' => ['required', new ExistsAsKeyInArray($db['pumps'])], // article num main
            '1' => ['required', new ExistsAsKeyInArray($db['countries'])], // countries
            '2' => ['required', new ExistsAsKeyInArray($db['currencies'])], // currencies
            '3' => ['required', 'numeric']
        ], [
            '0' => __('validation.attributes.import.price_lists.article_num_main'),
            '1' => __('validation.attributes.import.price_lists.country'),
            '2' => __('validation.attributes.import.price_lists.currency'),
            '3' => __('validation.attributes.import.price_lists.price'),
        ], [], $files, 'pumps.index', __('flash.price_lists.imported'));
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
            'pump_id' => $this->db['pumps'][trim($entity[0])],
            'country_id' => $this->db['countries'][trim($entity[1])],
            'currency_id' => $this->db['currencies'][trim($entity[2])],
            'price' => trim($entity[3])
        ];
    }

    protected function import($sheet)
    {
        foreach (array_chunk($sheet, 100) as $chunkedSheet) {
            DB::table('pumps_price_lists')->upsert($chunkedSheet, ['pump_id', 'country_id'], ['price', 'currency_id']);
        }
    }
}
