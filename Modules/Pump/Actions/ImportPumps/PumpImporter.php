<?php

namespace Modules\Pump\Actions\ImportPumps;

use Illuminate\Validation\ValidationException;

abstract class PumpImporter
{
    protected array $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    abstract protected function rules(): array;

    abstract protected function messages(): array;

    abstract protected function attributes(): array;

    abstract protected function importEntity($entity): array;

    public function importCallback(&$errorBag): callable
    {
        return function($entity) use (&$errorBag)
        {
            try {
                validator()
                    ->make($entity, $this->rules(), $this->messages(), $this->attributes())
                    ->validate();
            } catch (ValidationException $exception) {
                array_map(function ($message) use ($entity, &$errorBag) {
                    $errorBag[] = $this->errorBagEntity($entity, $message);
                }, $exception->validator->errors()->messages());
            }
            if (empty($errorBar)) {
                return $this->importEntity($entity);
            }
        };
    }

    protected function errorBagEntity($entity, $message): array
    {
        return [
            'head' => [
                'key' => __('validation.attributes.import.pumps.article_num_main'),
                'value' => $entity[0] !== "" ? $entity[0] : 'Unknown',
            ],
            'file' => '', // TODO
            'message' => $message[0],
        ];
    }

    protected function idsArrayFromString($string, $separator = ","): array
    {
        return array_map(fn($id) => trim($id), explode($separator, $string));
    }
}
