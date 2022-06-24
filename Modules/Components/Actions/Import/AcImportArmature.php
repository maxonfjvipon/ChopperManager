<?php

namespace Modules\Components\Actions\Import;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\NotEmptyStr;
use DB;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;

/**
 * Import armature action.
 */
final class AcImportArmature extends AcImport
{
    /**
     * @param array $sheet
     * @return void
     * @throws Exception
     */
    protected function import(array $sheet): void
    {
        DB::table('armature')->upsert($sheet, ['article']);
        Armature::clearCache();
    }

    /**
     * @param array $entity
     * @return array[]
     */
    protected function rules(array $entity): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // article
            '1' => ['required', new In(ArmatureType::getDescriptions())], // armature type
            '2' => ['required', new In(ConnectionType::getDescriptions())], // connection type
            '3' => ['required', new In(DN::values())], // dn
            '4' => ['required', 'numeric', new NotEmptyStr()], // length
            '5' => ['required', 'numeric', new NotEmptyStr()], // weight
            '6' => ['required', 'numeric', new NotEmptyStr()], // price
            '7' => ['required', new In(Currency::getKeys())], // currency
        ];
    }

    /**
     * @return string[]
     */
    protected function attributes(): array
    {
        return [
            '0' => 'Артикул',
            '1' => 'Тип арматуры',
            '2' => 'Тип соединения',
            '3' => 'ДУ',
            '4' => 'Длина',
            '5' => 'Масса',
            '6' => 'Цена',
            '7' => 'Валюта'
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
            'type' => ArmatureType::getValueByDescription($entity[1]),
            'connection_type' => ConnectionType::getValueByDescription($entity[2]),
            'dn' => $entity[3],
            'length' => $entity[4],
            'weight' => $entity[5],
            'price' => $entity[6],
            'currency' => Currency::getValue($entity[7])
        ];
    }
}
