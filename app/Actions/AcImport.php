<?php

namespace App\Actions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Validation\ValidationException;

/**
 * Import action
 */
abstract class AcImport
{
    private const MAX_EXECUTION_TIME = 180;

    /**
     * Ctor.
     * @param array $files
     * @param array $db
     */
    public function __construct(private array $files, protected array $db = [])
    {
    }

    /**
     * @return RedirectResponse
     */
    public function execute(): RedirectResponse
    {
        ini_set('max_execution_time', self::MAX_EXECUTION_TIME);
        $errorBag = [];
        $files = [];
        try {
            foreach ($this->files as $file) {
                $files[] = (new FastExcel())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->sheet(0)
                    ->importSheets($file, function ($entity) use (&$errorBag) {
                        try {
                            validator()
                                ->make($entity, $this->rules($entity), $this->messages(), $this->attributes())
                                ->validate();
                        } catch (ValidationException $exception) {
                            array_map(function ($message) use ($entity, &$errorBag) {
                                $errorBag[] = $this->errorBagEntity($entity, $message);
                            }, $exception->validator->errors()->messages());
                        }
                        if (!empty($errorBag)) {
                            throw new Exception("Error bag is not empty"); // todo
                        }
                        return $this->entityToImport($entity);
                    });
            }
            foreach ($files as $fileSheets) {
                foreach ($fileSheets as $sheet) {
                    $this->import($sheet);
                }
            }
            return Redirect::back()->with('success', 'Загрузка прошла успешно');
        } catch (Exception $exception) {
            dd($exception, $errorBag);
            return Redirect::back()->with('errorBag', array_splice($errorBag, 0, 50));
        }
    }

    /**
     * @param array $sheet
     * @return mixed
     */
    abstract protected function import(array $sheet);

    /**
     * @param array $entity
     * @param array $message
     * @return array
     */
    protected function errorBagEntity(array $entity, array $message): array
    {
        return [
            'head' => [
                'key' => 'Артикул',
                'value' => $entity[0] !== "" ? $entity[0] : "Unknown",
            ],
            'message' => $message[0]
        ];
    }

    /**
     * @param array $entity
     * @return array
     */
    abstract protected function rules(array $entity): array;

    /**
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * @return array
     */
    abstract protected function attributes(): array;

    /**
     * @param array $entity
     * @return array
     */
    abstract protected function entityToImport(array $entity): array;

}
