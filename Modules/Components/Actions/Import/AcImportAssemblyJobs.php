<?php

namespace Modules\Components\Actions\Import;

use App\Actions\AcImport;
use App\Models\Enums\Currency;
use App\Rules\NotEmptyStr;
use DB;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Components\Entities\AssemblyJob;
use Modules\Components\Entities\CollectorType;
use Modules\Components\Entities\ControlSystemType;

/**
 * Import assembly jobs action.
 */
final class AcImportAssemblyJobs extends AcImport
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function import(array $sheet): void
    {
        DB::table('assembly_jobs')->upsert($sheet, ['article']);
        AssemblyJob::clearCache();
    }

    /**
     * @param array $entity
     * @return array[]
     * @throws Exception
     */
    protected function rules(array $entity): array
    {
        return [
            '0' => ['required', new NotEmptyStr()], // article
            '1' => ['required', new In(CollectorType::getDescriptions())], // collector type
            '2' => ['required', new In(ControlSystemType::allOrCached()->pluck('name')->all())], // control system type
            '3' => ['required', 'numeric', new NotEmptyStr()], // pumps count
            '4' => ['required', 'numeric', new NotEmptyStr()], // pumps weight
            '5' => ['required', 'numeric', new NotEmptyStr()], // price
            '6' => ['required', new In(Currency::getKeys())], // currency
        ];
    }

    /**
     * @inheritDoc
     */
    protected function attributes(): array
    {
        return [
            '0' => "Артикул",
            '1' => "Тип коллектора",
            '2' => "Система управления",
            '3' => "Количество насосов",
            '4' => "Допустимая масса насоса",
            '5' => "Цена",
            '6' => "Валюта",
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
            'collector_type' => CollectorType::getValueByDescription($entity[1]),
            'control_system_type_id' => ControlSystemType::allOrCached()->firstWhere('name', $entity[2])->id,
            'pumps_count' => $entity[3],
            'pumps_weight' => $entity[4],
            'price' => $entity[5],
            'currency' => Currency::getValue($entity[6])
        ];
    }
}
