<?php

namespace App\Actions;

use App\Interfaces\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Import action.
 */
abstract class AcImport implements Take
{
    private const MAX_EXECUTION_TIME = 180;

    /**
     * Ctor.
     */
    public function __construct(private array $files, protected array $db = [])
    {
    }

    public function act(Request $request = null): Responsable|Response
    {
        ini_set('max_execution_time', self::MAX_EXECUTION_TIME);
        $errorBag = [];
        $files = [];
        try {
            foreach ($this->files as $file) {
                $files[] = (new FastExcel())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->import($file, function ($entity) use (&$errorBag) {
                        try {
                            validator()
                                ->make($entity, $this->rules($entity), $this->messages(), $this->attributes())
                                ->validate();
                        } catch (ValidationException $exception) {
                            array_map(function ($message) use ($entity, &$errorBag) {
                                $errorBag[] = $this->errorBagEntity($entity, $message);
                            }, $exception->validator->errors()->messages());
                        }
                        if (! empty($errorBag)) {
                            throw new Exception('Error bag is not empty'); // todo
                        }

                        return $this->entityToImport($entity);
                    });
            }
            foreach ($files as $sheet) {
                $this->import($sheet->toArray());
            }

            return Redirect::back()->with('success', 'Загрузка прошла успешно');
        } catch (Exception $exception) {
            dd($exception, $errorBag);

            return Redirect::back()->with('errorBag', array_splice($errorBag, 0, 50));
        }
    }

    abstract protected function import(array $sheet): void;

    protected function errorBagEntity(array $entity, array $message): array
    {
        return [
            'head' => [
                'key' => 'Артикул',
                'value' => '' !== $entity[0] ? $entity[0] : 'Unknown',
            ],
            'message' => $message[0],
        ];
    }

    abstract protected function rules(array $entity): array;

    protected function messages(): array
    {
        return [];
    }

    abstract protected function attributes(): array;

    abstract protected function entityToImport(array $entity): array;
}
