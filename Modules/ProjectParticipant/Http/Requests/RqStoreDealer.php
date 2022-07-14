<?php

namespace Modules\ProjectParticipant\Http\Requests;

use App\Rules\ArrayExistsInArray;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Entities\Area;

/**
 * Store dealer request.
 */
class RqStoreDealer extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'area_id' => ['required', new In(Area::allOrCached()->pluck('id')->all())],
            'itn' => ['sometimes', 'nullable', 'max:12', 'unique:dealers,itn'],
            'email' => ['sometimes', 'nullable', 'string', 'email', 'max:255', 'unique:dealers,email'],
            'phone' => ['sometimes', 'nullable', 'max:12'],
            'available_series_ids' => ['sometimes', 'nullable', 'array',  new ArrayExistsInArray(PumpSeries::pluck('id')->all())],
        ];
    }

    public function dealerProps(): array
    {
        return [
            'name' => $this->name,
            'area_id' => $this->area_id,
            'itn' => $this->itn,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
