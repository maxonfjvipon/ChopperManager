<?php

namespace Modules\Pump\Http\Requests;

use App\Rules\ArrayExistsInArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\PumpOrientation;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * @property string            $sortOrder
 * @property string            $sortField
 * @property string            $search
 * @property array<string|int> $brand
 * @property array<string|int> $series
 * @property array<string|int> $collector_switch
 * @property array<string|int> $connection_type
 * @property array<string|int> $dn_suction
 * @property array<string|int> $dn_pressure
 * @property array<string|int> $orientation
 * @property array<string|int> $pagination
 */
final class RqLoadPumps extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->sortOrder) {
            $this->merge(['sortOrder' => 'ascend' === $this->sortOrder ? 'ASC' : 'DESC']);
        }
        $this->merge(['need_info' => true]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sortField' => ['sometimes', 'nullable', new In([
                'price',
                'weight',
                'power',
                'current',
                'dn_suction',
                'dn_pressure',
                'suction_height',
                'ptp_length',
            ])],
            'sortOrder' => ['sometimes', 'nullable', new In(['ASC', 'DESC'])],
            'pagination' => [
                'sometimes',
                'nullable',
                'array',
                //                new ArrayExistsInArray(['current', 'pageSize', 'pageSizeOptions'])
            ],
            'brand' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(PumpBrand::pluck('id')->all())],
            'series' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(PumpSeries::pluck('id')->all())],
            'collector_switch' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(CollectorSwitch::getValues())],
            'connection_type' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(ConnectionType::getValues())],
            'dn_suction' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(DN::values())],
            'dn_pressure' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(DN::values())],
            'orientation' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(PumpOrientation::getValues())],
            'search' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
