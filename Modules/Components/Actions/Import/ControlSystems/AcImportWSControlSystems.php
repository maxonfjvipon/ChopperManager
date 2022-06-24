<?php

namespace Modules\Components\Actions\Import\ControlSystems;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\NotEmptyStr;
use BenSampo\Enum\Rules\EnumKey;
use Exception;
use Illuminate\Validation\Rules\In;

/**
 * Import WS control systems action.
 */
final class AcImportWSControlSystems extends AcImport
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
            '1' => ['required', new In(["WS"])], // WS/AF
            '2' => ['required', new In(array_keys($this->db['control_system_types']))], // control system type
            '3' => ['required', 'numeric', new NotEmptyStr()], // pumps count
            '4' => ['required', 'numeric', new NotEmptyStr()], // power
            '5' => ['required', 'numeric', new NotEmptyStr()], // weight
            '6' => ['required', 'nullable', 'string'], // description
            '7' => ['required', 'nullable', new NotEmptyStr()], // price
            '8' => ['required', new EnumKey(Currency::class)], // currency
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
            "5" => "Масса",
            "6" => "Описание",
            "7" => "Цена",
            "8" => "Валюта"
        ];
    }

    /**
     * @inheritDoc
     */
    protected function entityToImport(array $entity): array
    {
        return [
            'article' => trim($entity[0]),
            'type_id' => $this->db['control_system_types'][$entity[2]],
            'pumps_count' => $entity[3],
            'power' => $entity[4],
            'weight' => $entity[5],
            'description' => $entity[6],
            'price' => $entity[7],
            'currency' => Currency::getValue($entity[8])
        ];
    }
}
