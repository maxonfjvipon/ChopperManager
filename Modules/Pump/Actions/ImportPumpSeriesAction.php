<?php


namespace Modules\Pump\Actions;


use App\Rules\ExistsAsKeyInArray;
use App\Rules\ExistsInArray;
use App\Rules\ExistsInIdsArray;
use Illuminate\Support\Facades\DB;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpSeriesAndApplication;
use Modules\Pump\Entities\PumpSeriesAndType;
use Modules\Pump\Entities\PumpType;

class ImportPumpSeriesAction extends ImportAction
{
    public function __construct($files)
    {
        $db = [
            'brands' => PumpBrand::pluck('id', 'name')->all(),
            'categories' => PumpCategory::pluck('id')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('id')->all(),
            'applications' => PumpApplication::pluck('id')->all(),
            'types' => PumpType::pluck('id')->all(),
        ];
        parent::__construct($db, [
            '0' => ['required', new ExistsAsKeyInArray($db['brands'])], // brand name
            '1' => ['required'], // series name
            '2' => ['required', new ExistsInArray($db['categories'])], // category
            '3' => ['required', new ExistsInArray($db['power_adjustments'])], // power adjustment
            '4' => ['required', new ExistsInIdsArray($db['applications'], ",")], // applications
            '5' => ['required', new ExistsInIdsArray($db['types'], ",")], // types
            '6' => ['sometimes', 'nullable', 'string'], // icon
        ], [
            // TODO: атрибуты для серий
            '0' => __('validation.attributes.import.pump_series.brand'),
            '1' => __('validation.attributes.import.pump_series.name'),
            '2' => __('validation.attributes.import.pump_series.category'),
            '3' => __('validation.attributes.import.pump_series.power_adjustment'),
            '4' => __('validation.attributes.import.pump_series.applications'),
            '5' => __('validation.attributes.import.pump_series.types'),
            '6' => __('validation.attributes.import.pump_series.icon'),
        ], [], $files, 'pump_series.index', __('flash.pump_series.imported'));
    }

    protected function errorBagEntity($entity, $message): array
    {
        return [
            'head' => [
                'key' => __('validation.attributes.import.pump_series.name'),
                'value' => ($entity[0] !== "" && $entity[1] !== "")
                    ? $entity[0] . ' ' . $entity[1]
                    : 'Unknown series',
            ],
            'file' => '', // TODO
            'message' => $message[0],
        ];
    }

    protected function importEntity($entity): array
    {
        return [
            'series' => [
                'brand_id' => $this->db['brands'][$entity[0]],
                'name' => trim($entity[1]),
                'category_id' => $entity[2],
                'power_adjustment_id' => $entity[3],
                'image' => array_key_exists(6, $entity) ? trim($entity[6]) : null,
            ],
            'application_ids' => $this->idsArrayFromString($entity[4]),
            'type_ids' => $this->idsArrayFromString($entity[5]),
        ];
    }

    protected function import($sheet)
    {
        foreach ($sheet as $row) {
            $series = PumpSeries::updateOrCreate([
                'brand_id' => $row['series']['brand_id'],
                'name' => $row['series']['name']
            ], $row['series']);
            PumpSeriesAndType::updateForSeries($series, $row['type_ids']);
            PumpSeriesAndApplication::updateForSeries($series, $row['application_ids']);
        }
    }
}