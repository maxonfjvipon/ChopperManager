<?php

namespace Modules\Components\Actions\Import\ControlSystems;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\NotEmptyStr;
use BenSampo\Enum\Rules\EnumKey;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Selection\Entities\MontageType;

/**
 * Import AF control systems action.
 */
final class AcImportAFControlSystems extends AcImport
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function import(array $sheet): void
    {
        throw new Exception("This method should never be called");
    }

    /**
     * @inheritDoc
     */
    protected function rules(array $entity): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // article
            '1' => ['required', new In(["AF"])], // WS/AF
            '2' => ['required', new In(array_keys($this->db['control_system_types']))], // control system type
            '3' => ['required', 'numeric', new NotEmptyStr()], // pumps count
            '4' => ['required', 'numeric', new NotEmptyStr()], // power
            '5' => ['required', 'in:0,1'], // avr
            '6' => ['required', 'in:0,1,2'], // gate valves count
            '7' => ['required', 'in:0,1'], // kkv
            '8' => ['required', 'in:0,1'], // has jockey
            '9' => ['required', new In(array_keys($this->db['on_street']))], // on street
            '10' => ['required', new In(MontageType::getDescriptions())], // montage type
            '11' => ['required', 'numeric', new NotEmptyStr()], // weight
            '12' => ['required', 'nullable', 'string'], // description
            '13' => ['required', 'nullable', new NotEmptyStr()], // price
            '14' => ['required', new EnumKey(Currency::class)], // currency
        ];
    }

    /**
     * @inheritDoc
     */
    protected function attributes(): array
    {
        return [
            '0' => "Артикул",
            "1" => "Тип станции",
            "2" => "Тип системы управления",
            "3" => "Количество насосов",
            "4" => "Мощность",
            "5" => "АВР",
            "6" => "Количество задвижек",
            "7" => "ККВ",
            "8" => "Жокей",
            "9" => "Исполнение",
            "10" => "Монтаж",
            "11" => "Масса",
            "12" => "Описание",
            "13" => "Цена",
            "14" => "Валюта"
        ];
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function entityToImport(array $entity): array
    {
        return [
            'article' => trim($entity[0]),
            'type_id' => $this->db['control_system_types'][$entity[2]],
            'pumps_count' => $entity[3],
            'power' => $entity[4],
            'avr' => boolval($entity[5]),
            'gate_valves_count' => $entity[6],
            'kkv' => boolval($entity[7]),
            'has_jockey' => boolval($entity[8]),
            'on_street' => $this->db['on_street'][$entity[9]],
            'montage_type' => MontageType::getValueByDescription($entity[10]),
            'weight' => $entity[11],
            'description' => $entity[12],
            'price' => $entity[13],
            'currency' => Currency::getValue($entity[14])
        ];
    }
}
