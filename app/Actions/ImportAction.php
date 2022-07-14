<?php

namespace App\Actions;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;

abstract class ImportAction
{
    protected array $db;

    protected array $rules;

    protected array $attributes;

    protected array $messages;

    protected array $files;

    private int $MAX_EXECUTION_TIME = 180;

    protected string $redirectRouteName;

    protected string $flashMessageSuccess;

    public function __construct($db, $rules, $attributes, $messages, $files, $redirectRouteName, $flashMessageSuccess)
    {
        $this->db = $db;
        $this->rules = $rules;
        $this->attributes = $attributes;
        $this->messages = $messages;
        $this->files = $files;
        $this->redirectRouteName = $redirectRouteName;
        $this->flashMessageSuccess = $flashMessageSuccess;
    }

    protected function idsArrayFromString($string, $separator = ','): array
    {
        return array_map(fn ($id) => trim($id), explode($separator, $string));
    }

    public function execute(): RedirectResponse
    {
        ini_set('max_execution_time', $this->MAX_EXECUTION_TIME);
        $errorBag = [];
        try {
            $files = [];
            foreach ($this->files as $file) {
                $files[] = (new FastExcel())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->importSheets($file, function ($entity) use (&$errorBag) {
                        try {
                            validator()
                                ->make($entity, $this->rules, $this->messages, $this->attributes)
                                ->validate();
                        } catch (ValidationException $exception) {
                            array_map(function ($message) use ($entity, &$errorBag) {
                                $errorBag[] = $this->errorBagEntity($entity, $message);
                            }, $exception->validator->errors()->messages());
                        }
                        if (empty($errorBag)) {
                            return $this->importEntity($entity);
                        }
                    });
            }
            if (! empty($errorBag)) {
                return Redirect::back()->with('errorBag', array_splice($errorBag, 0, 50));
            }
            foreach ($files as $fileSheets) {
                foreach ($fileSheets as $sheet) {
                    $this->import($sheet);
                }
            }

            return Redirect::back()->with('success', 'Import was successful');
        } catch (IOException|UnsupportedTypeException|ReaderNotOpenedException|Exception $exception) {
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());

            return Redirect::route($this->redirectRouteName)
                ->withErrors(__('validation.import.exception', ['attribute' => $this->flashMessageSuccess]));
        }
    }

    /**
     * @throws Exception
     */
    abstract protected function errorBagEntity($entity, $message): array;

    /**
     * @throws Exception
     */
    abstract protected function importEntity($entity): array;

    /**
     * @throws Exception
     */
    abstract protected function import($sheet): void;
}
