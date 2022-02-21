<?php

namespace Modules\Pump\Actions\ImportPumps;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maxonfjvipon\Elegant_Elephant\Logical\EqualityOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;

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
        return function ($entity) use (&$errorBag) {
            try {
                Validator::make($entity, $this->rules(), $this->messages(), $this->attributes())->validate();
            } catch (ValidationException $exception) {
                array_map(function ($message) use ($entity, &$errorBag) {
                    $errorBag[] = $this->errorBagEntity($entity, $message);
                }, $exception->validator->errors()->messages());
            }
            if ((new EqualityOf(new LengthOf($errorBag), 0))->asBool()) {
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
