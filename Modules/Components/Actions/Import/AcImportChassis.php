<?php

namespace Modules\Components\Actions\Import;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\NotEmptyStr;
use BenSampo\Enum\Rules\EnumKey;
use DB;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\Chassis;

final class AcImportChassis extends AcImport
{

    /**
     * @param array $sheet
     * @return void
     * @throws Exception
     */
    protected function import(array $sheet)
    {
        DB::table('chassis')->upsert($sheet, ['article']);
        Chassis::clearCache();
    }

    /**
     * @param array $entity
     * @return array[]
     */
    protected function rules(array $entity): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // article
            '1' => ['required', 'numeric', new NotEmptyStr()], // pumps count
            '2' => ['required', 'numeric', new NotEmptyStr()], // available pumps weight
            '3' => ['required', 'numeric', new NotEmptyStr()], // weight
            '4' => ['required', 'numeric', new NotEmptyStr()], // price
            '5' => ['required', new EnumKey(Currency::class)], // currency
        ];
    }

    /**
     * @inheritDoc
     */
    protected function attributes(): array
    {
        return [
            '0' => 'Артикул',
            '1' => 'Количество насосов',
            '2' => 'Допустимая масса насосов',
            '3' => 'Масса',
            '4' => 'Цена',
            '5' => 'Валюта',
        ];
    }

    /**
     * @param array $entity
     * @return array
     */
    protected function entityToImport(array $entity): array
    {
        return [
            'article' => trim($entity[0]),
            'pumps_count' => $entity[1],
            'pumps_weight' => $entity[2],
            'weight' => $entity[3],
            'price' => $entity[4],
            'currency' => Currency::getValue($entity[5])
        ];
    }
}
