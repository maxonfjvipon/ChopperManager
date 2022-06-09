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
 * @property-read string $sortOrder
 * @property-read string $sortField
 * @property-read string $search
 * @property-read array<string|int> $brand
 * @property-read array<string|int> $series
 * @property-read array<string|int> $collector_switch
 * @property-read array<string|int> $connection_type
 * @property-read array<string|int> $dn_suction
 * @property-read array<string|int> $dn_pressure
 * @property-read array<string|int> $orientation
 * @property-read array<string|int> $pagination
 */
final class RqLoadPumps extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->sortOrder) {
            $this->merge(['sortOrder' => $this->sortOrder === 'ascend' ? 'ASC' : "DESC"]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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
                'ptp_length'
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
            'search' => ['sometimes', 'nullable', 'string']
        ];
    }
}
