<?php

namespace Modules\Pump\Actions;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class ImportAction
{
    use UsesTenantModel;

    protected array $db, $rules, $attributes, $messages, $files;
    private int $MAX_EXECUTION_TIME = 180;
    protected string $redirectRouteName, $flashMessageSuccess;

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

    public function execute()
    {
        ini_set('max_execution_time', $this->MAX_EXECUTION_TIME);
        $errorBar = [];
        try {
            $sheets = [];
            foreach ($this->files as $file) {
                $sheets = (new FastExcel())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->importSheets($file, function ($entity) use (&$errorBar) {
//                        $keysCount = count(array_keys($entity));
//                        if ($keysCount != 23) {
//                            for ($index = $keysCount; $index < 23; $index++) {
//                                $entity[$index] = null;
//                            }
//                        }
                        try {
                            validator()->make($entity, $this->rules, $this->messages, $this->attributes)->validate();
                        } catch (ValidationException $exception) {
                            array_map(function ($message) use ($entity, &$errorBar) {
                                $errorBar[] = $this->errorBagEntity($entity, $message);
                            }, $exception->validator->errors()->messages());
                        }
                        if (empty($errorBar)) {
                            return $this->importEntity($entity);
                        }
                    });
            }
            if (!empty($errorBar)) {
                return Redirect::back()->with('errorBag', array_splice($errorBar, 0, 50));
            }
            foreach ($sheets as $sheet) {
                $this->import($sheet);
            }
            return Redirect::back()->with('success', 'Import was successful');
        } catch (IOException | UnsupportedTypeException | ReaderNotOpenedException | Exception $exception) {
            Log::error($exception->getMessage());
            return Redirect::route($this->redirectRouteName)
                ->withErrors(__('validation.import.exception', ['attribute' => $this->flashMessageSuccess]));
        }
    }

    private function throwOverwrittenException()
    {
        throw new Exception("Method should be overwritten");
    }

    protected function errorBagEntity($entity, $message)
    {
        $this->throwOverwrittenException();
    }

    protected function importEntity($entity)
    {
        $this->throwOverwrittenException();
    }

    protected function import($sheet)
    {
        $this->throwOverwrittenException();
    }

}
