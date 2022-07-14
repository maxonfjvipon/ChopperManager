<?php

namespace Modules\Components\Actions\Import;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\NotEmptyStr;
use DB;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\CollectorType;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;

/**
 * Import collectors action.
 */
final class AcImportCollectors extends AcImport
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    protected function import(array $sheet): void
    {
        DB::table('collectors')->upsert($sheet, ['article']);
        Collector::clearCache();
    }

    /**
     * @return array[]
     */
    protected function rules(array $entity): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // article
            '1' => ['required', new In(CollectorType::getDescriptions())], // collector type
            '2' => ['required', new In(DN::values())], // dn common
            '3' => ['required', new In(DN::values())], // dn pipes
            '4' => ['required', 'numeric', new NotEmptyStr()], // pipes count
            '5' => ['required', new In(ConnectionType::getDescriptions())], // connection type
            '6' => ['required', new In(CollectorMaterial::getDescriptions())], // collector material
            '7' => ['required', 'numeric', new NotEmptyStr()], // price
            '8' => ['required', new In(Currency::getKeys())], // currency
            '9' => ['required', 'numeric', new NotEmptyStr()], // weight
            '10' => ['required', 'numeric', new NotEmptyStr()], // length
            '11' => ['required', 'numeric', new NotEmptyStr()], // pipes length
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function attributes(): array
    {
        return [
            '0' => 'Артикул',
            '1' => 'Тип коллектора',
            '2' => 'ДУ общий',
            '3' => 'ДУ патрубков',
            '4' => 'Количество патрубков',
            '5' => 'Тип соединения',
            '6' => 'Материал',
            '7' => 'Цена',
            '8' => 'Валюта',
            '9' => 'Масса',
            '10' => 'Длина',
            '11' => 'Длина патрубков',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    protected function entityToImport(array $entity): array
    {
        return [
            'article' => trim($entity[0]),
            'type' => CollectorType::getValueByDescription($entity[1]),
            'dn_common' => $entity[2],
            'dn_pipes' => $entity[3],
            'pipes_count' => $entity[4],
            'connection_type' => ConnectionType::getValueByDescription($entity[5]),
            'material' => CollectorMaterial::getValueByDescription($entity[6]),
            'price' => $entity[7],
            'currency' => Currency::getValue($entity[8]),
            'weight' => $entity[9],
            'length' => $entity[10],
            'pipes_length' => $entity[11],
        ];
    }
}
