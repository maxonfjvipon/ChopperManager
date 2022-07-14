<?php

namespace Modules\Components\Actions\Import\ControlSystems;

use App\Actions\AcImport;
use DB;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\ValidationException;
use Modules\Components\Entities\ControlSystem;
use Modules\Components\Entities\ControlSystemType;

/**
 * Import control systems action.
 */
final class AcImportControlSystems extends AcImport
{
    private string $type = '';

    /**
     * @throws Exception
     */
    public function __construct(array $files)
    {
        $db = ['control_system_types' => ControlSystemType::allOrCached()->pluck('id', 'name')->all()];
        parent::__construct($files, [
            'WS' => new AcImportWSControlSystems($files, $db),
            'AF' => new AcImportAFControlSystems($files, array_merge(
                    $db,
                    [
                        'on_street' => [
                            'Indoor' => false,
                            'Outdoor' => true,
                        ],
                    ]
                )
            ),
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    protected function import(array $sheet): void
    {
        DB::table('control_systems')->upsert($sheet, ['article']);
        ControlSystem::clearCache();
    }

    /**
     * @return array[]
     *
     * @throws ValidationException|BindingResolutionException
     */
    protected function rules(array $entity): array
    {
        validator()->make($entity, [
            '0' => ['required'],
            '1' => ['required', new In(array_keys($this->db))],
        ], [], [
            1 => 'Тип станции',
        ])->validate();
        $this->type = $entity[1];

        return $this->db[$this->type]->rules($entity);
    }

    /**
     * {@inheritDoc}
     */
    protected function attributes(): array
    {
        return $this->db[$this->type]->attributes();
    }

    /**
     * {@inheritDoc}
     */
    protected function entityToImport(array $entity): array
    {
        return $this->db[$this->type]->entityToImport($entity);
    }
}
