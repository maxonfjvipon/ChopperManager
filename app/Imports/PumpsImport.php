<?php

namespace App\Imports;

use App\Models\ConnectionType;
use App\Models\Currency;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpCategory;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpRegulation;
use App\Models\Pumps\PumpSeries;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class PumpsImport implements ToModel, WithValidation, WithStartRow, WithUpserts, WithBatchInserts
{
    use Importable;

    /**
     * @param array $row
     * @return Pump
     */
    public function model(array $row): Pump
    {
        return new Pump([
            'part_num_main' => trim($row[0]),
            'part_num_backup' => trim($row[1]) ?? null,
            'part_num_archive' => trim($row[2]) ?? null,
            'series_id' => PumpSeries::whereName($row[4])
                ->whereProducerId(PumpProducer::whereName($row[3])->first()->id)
                ->first()
                ->id,
            'name' => trim($row[5]),
            'price' => $row[6],
            'currency_id' => Currency::whereName($row[7])->first()->id,
            'weight' => $row[8],
            'power' => $row[9],
            'amperage' => $row[10],
            'connection_type_id' => ConnectionType::where('name->ru', $row[11])->first()->id,
            'dn_input_id' => DN::whereValue($row[12])->first()->id,
            'dn_output_id' => DN::whereValue($row[13])->first()->id,
            'min_liquid_temp' => $row[14],
            'max_liquid_temp' => $row[15],
            'between_axes_dist' => $row[16],
            'performance' => trim($row[17]),
            'category_id' => PumpCategory::where('name->ru', $row[18])->first()->id,
            'regulation_id' => PumpRegulation::where('name->ru', $row[19])->first()->id,
            'phase_id' => CurrentPhase::whereValue($row[20])->first()->id,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function uniqueBy(): string
    {
        return 'part_num_main';
    }

    public function rules(): array
    {
        return [
            '0' => ['required'], // main part num
            '1' => ['nullable'], // backup part num
            '2' => ['nullable'], // archive part num
            '3' => ['required', 'exists:pump_producers,name'], // producer,
            '4' => ['required', 'exists:pump_series,name'], // series // todo: check if series exist for producer
            '5' => ['required'], // name
            '6' => ['required'], // price
            '7' => ['required', 'exists:currencies,name'], // currency
            '8' => ['required'], // weight
            '9' => ['required'], // power,
            '10' => ['required'], // amperage
//            '11' => ['required', 'exists:connection_types,name'], // connection type
            '11' => ['required', 'unique_translation:connection_types,name'], // connection type
            '12' => ['required', 'exists:dns,value'], // dn
            '13' => ['required', 'exists:dns,value'], // dn
            '14' => ['required'], // min temp
            '15' => ['required'], // max temp
            '16' => ['required'], // between axes
            '17' => ['required'], // performance // todo: check if even count of spaces between digits
//            '18' => ['required', 'exists:pump_categories,name'], // category
//            '19' => ['required', 'exists:pump_regulations,name'], // regulation
            '18' => ['required', 'unique_translation:pump_categories,name'], // category
            '19' => ['required', 'unique_translation:pump_regulations,name'], // regulation
            '20' => ['required', 'exists:current_phases,value'], // phase
            '21' => ['required'], // application // todo: check if exists in pump_applications table
            '22' => ['required'] // type // todo: check if exists in pump_filter_types table
        ];
    }

    /**
     * @return array - attributes
     */
    public function customValidationAttributes(): array
    {
        return [
            '0' => 'pump.part_num_main',
            '1' => 'pump.part_num_backup',
            '2' => "pump.part_num_archive",
            '3' => "pump.producer",
            '4' => "pump.series",
            '5' => "pump.name",
            '6' => "pump.price",
            '7' => "pump.currency",
            '8' => "pump.weight",
            '9' => "pump.power",
            '10' => "pump.amperage",
            '11' => 'pump.connection_type',
            '12' => 'pump.dn_input',
            '13' => 'pump.dn_output',
            '14' => 'pump.min_temp',
            '15' => 'pump.max_temp',
            '16' => 'pump.between_axes_dist',
            '17' => 'pump.performance',
            '18' => 'pump.category',
            '19' => 'pump.regulation',
            '20' => 'pump.phase',
            '21' => 'pump.applications',
            '22' => 'pump.types'
        ];
    }

    /**
     * @return array - messages
     */
//    public function customValidationMessages(): array
//    {
//        return [
//            '0.required' => 'Отсутствует основной артикул',
//            '3.required' => 'Отсутствует производитель',
//            '4.required' => 'Отсутствует серия',
//            '5.required' => 'Отсутствует наименование',
//            '6.required' => 'Отсутствует цена',
//            '7.required' => 'Отсутствует валюта',
//            '8.required' => 'Отсутствует вес',
//            '9.required' => 'Отсутствует мощность',
//            '10.required' => 'Отсутствует ток',
//            '11.required' => 'Отсутствует тип соединения',
//            '12.required' => 'Отсутствует ДУ входа',
//            '13.required' => 'Отсутствует ДУ выхода',
//            '14.required' => 'Отсутствует минимальная температура',
//            '15.required' => 'Отсутствует максимальная температура',
//            '16.required' => 'Отсутствует межосевое расстояние',
//            '17.required' => 'Отсутствует характеристика',
//            '18.required' => 'Отсутствуют категория',
//            '19.required' => 'Отсутствует регулирование ',
//            '20.required' => 'Отсутствует фаза',
//            '21.required' => 'Отсутствуют применения ',
//            '22.required' => 'Отсутствуют типы',
//
//            '3.exists' => 'Производитель не найден в БД',
//            '4.exists' => 'Серия не найдена в БД',
//            '7.exists' => 'Валюта не найдена в БД',
//            '12.exists' => 'ДУ входа не найден в БД',
//            '13.exists' => 'ДУ выхода не найден в БД',
//            '20.exists' => 'Фаза не найдена в БД',
//
//            '11.unique_translation' => __('validation.exists', ['attribute' => ]),
//            '18.unique_translation' => 'Категория не найдена в БД',
//            '19.unique_translation' => 'Встроенное регулирование не найдено в БД',
//
//            '11.unique_translation' => 'Тип соединения не найден в БД',
//            '18.unique_translation' => 'Категория не найдена в БД',
//            '19.unique_translation' => 'Встроенное регулирование не найдено в БД',
//        ];
//    }
}
