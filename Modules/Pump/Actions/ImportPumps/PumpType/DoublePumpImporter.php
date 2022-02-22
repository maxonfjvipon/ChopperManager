<?php

namespace Modules\Pump\Actions\ImportPumps\PumpType;

use App\Rules\ExistsAsKeyInArray;
use App\Rules\ExistsInArray;
use App\Rules\NotEmptyStr;
use Illuminate\Validation\Rule;
use Modules\Pump\Actions\ImportPumps\PumpImporter;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;

class DoublePumpImporter extends PumpImporter
{
    public function __construct()
    {
        parent::__construct([
            'brands' => PumpBrand::pluck('id', 'name')->all(),
            'series' => PumpSeries::pluck('id', 'name')->all(),
            'connectionTypes' => ConnectionType::allOrCached()->pluck('id')->all(),
            'dns' => DN::allOrCached()->pluck('id', 'value')->all(),
            'mainsConnections' => MainsConnection::allOrCached()->pluck('id')->all(),
            'powerAdjustments' => ElPowerAdjustment::allOrCached()->pluck('id')->all(),
        ]);
    }

    protected function rules(): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // main part num
            '1' => ['nullable'], // backup part num
            '2' => ['nullable'], // archive part num
            '3' => ['required', Rule::in(array_keys($this->db['brands']))], // brand,
//            '3' => ['required', Rule::in(array_keys($this->db['brands'])) new ExistsAsKeyInArray($this->db['brands'])], // brand,
            '4' => ['required', new ExistsAsKeyInArray($this->db['series'])], // series
            '5' => ['required', new NotEmptyStr()], // name
            '6' => ['required', new NotEmptyStr()], // weight
            '7' => ['required', new NotEmptyStr()], // rated power,
            '8' => ['required', new NotEmptyStr()], // rated current
            '9' => ['required', new ExistsInArray($this->db['connectionTypes'])], // connection type
            '10' => ['required', new ExistsAsKeyInArray($this->db['dns'])], // dn
            '11' => ['required', new ExistsAsKeyInArray($this->db['dns'])], // dn
            '12' => ['required', new NotEmptyStr()], // min temp
            '13' => ['required', new NotEmptyStr()], // max temp
            '14' => ['required', new NotEmptyStr()], // ptp length
            '15' => ['required', new ExistsInArray($this->db['mainsConnections'])], // mains connection
            '16' => ['required', 'regex:/^\s*\d+((,|.)\d+)?(\s{1}\d+((,|.)\d+)?){7,59}\s*$/'], // standby performance
            '17' => ['required', 'regex:/^\s*\d+((,|.)\d+)?(\s{1}\d+((,|.)\d+)?){7,59}\s*$/'], // peak performance
            '18' => ['required', 'in:0,1'],
            '19' => ['sometimes', 'nullable',], // description
            '20' => ['sometimes', 'nullable', 'string'], // pump image
            '21' => ['sometimes', 'nullable', 'string'], // pump sizes image
            '22' => ['sometimes', 'nullable', 'string'], // pump electric diagram image
            '23' => ['sometimes', 'nullable', 'string'], // pump cross sectional drawing image
            '24' => ['sometimes', 'nullable', 'string'], // pump files
        ];
    }

    protected function messages(): array
    {
        return [];
    }

    protected function attributes(): array
    {
        // todo: change performances
        return [
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
            '16' => __('validation.attributes.import.pumps.dp_standby_performance'),
            '17' => __('validation.attributes.import.pumps.dp_peak_performance'),
            '18' => __('validation.attributes.import.pumps.is_discontinued'),
            '19' => __('validation.attributes.import.pumps.description'),
            '20' => __('validation.attributes.import.pumps.pump_image'),
            '21' => __('validation.attributes.import.pumps.pump_sizes_image'),
            '22' => __('validation.attributes.import.pumps.pump_electric_diagram_image'),
            '23' => __('validation.attributes.import.pumps.pump_cross_sectional_drawing_image'),
            '24' => __('validation.attributes.import.pumps.pump_files'),
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
                'dp_standby_performance' => str_replace(",", ".", trim($entity[16])),
                'dp_peak_performance' => str_replace(",", ".", trim($entity[17])),
                'is_discontinued' => $entity[18],
                'description' => $entity[19] ?? null,
                'image' => array_key_exists(20, $entity) ? trim($entity[20]) : null,
                'sizes_image' => array_key_exists(21, $entity) ? trim($entity[21]) : null,
                'electric_diagram_image' => array_key_exists(22, $entity) ? trim($entity[22]) : null,
                'cross_sectional_drawing_image' => array_key_exists(23, $entity) ? trim($entity[23]) : null,
                'pumpable_type' => Pump::$DOUBLE_PUMP,
                'deleted_at' => null
            ],
            'files' => array_key_exists(24, $entity) ? $this->idsArrayFromString($entity[24]) : []
        ];
    }
}
