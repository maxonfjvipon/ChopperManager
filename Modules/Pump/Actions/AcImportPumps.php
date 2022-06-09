<?php

namespace Modules\Pump\Actions;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\ExistsAsKeyInArray;
use App\Rules\NotEmptyStr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpOrientation;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;

final class AcImportPumps extends AcImport
{
    /**
     * Ctor.
     * @param array $files
     */
    public function __construct(array $files)
    {
        parent::__construct($files, [
            'brands' => PumpBrand::all()->pluck('id', 'name')->all(),
            'series' => PumpSeries::all()->pluck('id', 'name')->all(),
        ]);
    }

    /**
     * @param array $sheet
     * @return void
     * @throws Exception
     */
    protected function import(array $sheet): void
    {
        foreach (array_chunk($sheet, 100) as $chuckedSheet)
            DB::table('pumps')->upsert($chuckedSheet, ['article']);
        Pump::clearCache();
    }

    /**
     * @param array $entity
     * @return array[]
     */
    protected function rules(array $entity): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // article
            '1' => ['required', new ExistsAsKeyInArray($this->db['brands'])], // brand
            '2' => ['required', new ExistsAsKeyInArray($this->db['series'])], // series
            '3' => ['required', new NotEmptyStr()], // name
            '4' => ['required', 'numeric', new NotEmptyStr()], // price
            '5' => ['required', new In(Currency::getKeys())], // currency
            '6' => ['required', 'numeric', new NotEmptyStr()], // length
            '7' => ['required', 'numeric', new NotEmptyStr()], // height
            '8' => ['required', 'numeric', new NotEmptyStr()], // width
            '9' => ['required', 'numeric', new NotEmptyStr()], // suction height
            '10' => ['required', 'numeric', new NotEmptyStr()], // ptp length
            '11' => ['required', 'numeric', new NotEmptyStr()], // weight
            '12' => ['required', 'numeric', new NotEmptyStr()], // power
            '13' => ['required', 'numeric', new NotEmptyStr()], // current
            '14' => ['required', new In(ConnectionType::getDescriptions())], // connection type
            '15' => ['required', new In(PumpOrientation::getDescriptions())], // orientation
            '16' => ['required', new In(DN::values())], // dn suction
            '17' => ['required', new In(DN::values())], // dn pressure
            '18' => ['required', 'regex:/^\s*\d+((,|.)\d+)?(\s{1}\d+((,|.)\d+)?){7,59}\s*$/'], // performance
            '19' => ['required', new In(CollectorSwitch::getDescriptions())], // collector switch
//            '20' => ['required', new In([0, 1])] // is discontinued
        ];
    }

    /**
     * @return array
     */
    protected function attributes(): array
    {
        return [
            '0' => "Артикул",
            '1' => "Бренд",
            '2' => "Серия",
            '3' => "Наименование",
            '4' => "Цена",
            '5' => "Валюта",
            '6' => "Длина",
            '7' => "Высота",
            '8' => "Ширина",
            '9' => "Высота всаса",
            '10' => "Монтажная длина",
            '11' => "Масса",
            '12' => "Мощность",
            '13' => "Ток",
            '14' => "Тип соединения",
            '15' => "Ориентация",
            '16' => "ДУ входа",
            '17' => "ДУ выхода",
            '18' => "Гидравлическая характеристика",
            '19' => "Переход на коллектор",
//            '20' => "Активен",
        ];
    }

    /**
     * @param array $entity
     * @return array
     * @throws Exception
     */
    protected function entityToImport(array $entity): array
    {
        return [
            'article' => trim($entity[0]),
            'series_id' => $this->db['series'][$entity[2]],
            'name' => trim($entity[3]),
            'price' => $entity[4],
//            'currency' => Currency::getValue($entity[5]),
            'size' => implode("x", [$entity[6], $entity[7], $entity[8]]),
            'suction_height' => $entity[9],
            'ptp_length' => $entity[10],
            'weight' => $entity[11],
            'power' => $entity[12],
            'current' => $entity[13],
            'connection_type' => ConnectionType::getValueByDescription($entity[14]),
            'orientation' => PumpOrientation::getValueByDescription($entity[15]),
            'dn_suction' => $entity[16],
            'dn_pressure' => $entity[17],
            'performance' => str_replace(',', '.', trim($entity[18])),
            'collector_switch' => CollectorSwitch::getValueByDescription($entity[19])
        ];
    }
}
